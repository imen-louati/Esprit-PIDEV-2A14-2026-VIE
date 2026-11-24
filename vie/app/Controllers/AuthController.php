<?php

namespace App\Controllers;

use App\Core\Flash;
use App\Core\Mailer;
use App\Core\Security;
use App\Core\Validator;

class AuthController extends Controller
{
    protected string $page = 'login';
    protected string $title = 'VIE';

    private static ?string $dummyHash = null;
    private string $createdRole = '';

    public function home(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirectHome();
        }
        header('Location: home.php');
        exit;
    }

    public function login(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirectHome();
        }

        $errors = [];
        $old = ['email' => ''];

        if (Security::isPost()) {
            $old['email'] = $this->post('email');
            $errors = $this->attemptLogin();
            if ($errors === []) {
                $this->redirectHome();
            }
        }

        $this->renderFront('login', [
            'errors' => $errors,
            'old' => $old,
            'pageTitle' => 'Connexion — VIE',
            'currentPage' => 'login',
        ]);
    }

    private function attemptLogin(): array
    {
        if (!$this->csrfValid()) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }

        $config = require __DIR__ . '/../../config.php';
        $max = (int) $config['login_max_attempts'];
        $lock = (int) $config['login_lock_seconds'];
        $now = time();

        $attempts = array_values(array_filter(
            is_array($_SESSION['login_attempts'] ?? null) ? $_SESSION['login_attempts'] : [],
            fn($t) => ($now - (int) $t) < $lock
        ));
        if (count($attempts) >= $max) {
            $_SESSION['login_attempts'] = $attempts;
            return ['_form' => 'Trop de tentatives échouées. Patientez quelques minutes avant de réessayer.'];
        }

        $email = strtolower($this->post('email'));
        $password = $this->rawPost('password');

        if ($email === '' || $password === '') {
            return ['_form' => 'Saisissez votre adresse e-mail et votre mot de passe.'];
        }

        $user = $this->findUserByEmail($email);

        if (self::$dummyHash === null) {
            self::$dummyHash = password_hash('vie-dummy-password', PASSWORD_DEFAULT);
        }
        $hash = $user !== null ? $user->getPassword() : self::$dummyHash;
        $valid = password_verify($password, $hash) && $user !== null;

        if (!$valid) {
            $attempts[] = $now;
            $_SESSION['login_attempts'] = $attempts;
            return ['_form' => 'Adresse e-mail ou mot de passe incorrect.'];
        }

        if ($user->getStatus() === 'inactive') {
            if ($user->getRole() === 'medecin') {
                return ['_form' => 'Votre compte médecin est en attente de validation par l\'administration.'];
            }
            return ['_form' => 'Votre compte est inactif. Contactez l\'équipe VIE.'];
        }
        if ($user->getStatus() === 'blocked') {
            return ['_form' => 'Votre compte est bloqué. Contactez l\'équipe VIE.'];
        }

        if (password_needs_rehash($user->getPassword(), PASSWORD_DEFAULT)) {
            $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
            $stmt->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $user->getId()]);
        }

        session_regenerate_id(true);
        unset($_SESSION['login_attempts'], $_SESSION['csrf_token']);
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getFullName();
        $_SESSION['role'] = $user->getRole();
        $_SESSION['last_activity'] = time();

        $stmt = $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $user->getId()]);

        return [];
    }

    public function register(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirectHome();
        }

        $old = [
            'account' => 'personne',
            'person_type' => 'patient',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'birth_date' => '',
            'cancer_type' => '',
            'stage' => '',
            'progress' => '',
            'speciality' => '',
            'license_number' => '',
            'city' => '',
            'patient_code' => '',
            'relationship' => '',
        ];
        $errors = [];

        if (Security::isPost()) {
            foreach ($old as $key => $default) {
                $value = $this->post($key);
                $old[$key] = $value !== '' ? $value : $default;
            }
            $old['patient_code'] = strtoupper($old['patient_code']);
            $old['email'] = strtolower($old['email']);

            $errors = $this->registerUser($old);

            if ($errors === []) {
                if ($this->createdRole === 'admin') {
                    Flash::set('success', 'Compte administrateur créé. Vous êtes le premier utilisateur : connectez-vous pour gérer la plateforme.');
                } elseif ($this->createdRole === 'medecin') {
                    Flash::set('success', 'Compte créé. Un administrateur doit valider votre profil médecin avant votre première connexion.');
                } else {
                    Flash::set('success', 'Compte créé avec succès. Vous pouvez maintenant vous connecter.');
                }
                $this->redirect('login');
            }
        }

        $this->renderFront('register', [
            'errors' => $errors,
            'old' => $old,
            'firstAccount' => $this->countUsers() === 0,
            'specialities' => Validator::SPECIALITIES,
            'relationships' => Validator::RELATIONSHIPS,
            'cancerTypes' => Validator::CANCER_TYPES,
            'stages' => Validator::STAGES,
            'pageTitle' => 'Créer un compte — VIE',
            'currentPage' => 'register',
        ]);
    }

    private function registerUser(array $in): array
    {
        if (!$this->csrfValid()) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }

        $errors = [];
        $password = $this->rawPost('password');
        $confirm = $this->rawPost('password_confirm');
        $isFirst = $this->countUsers() === 0;

        if ($isFirst) {
            $role = 'admin';
        } elseif ($in['account'] === 'medecin') {
            $role = 'medecin';
        } elseif ($in['account'] === 'personne' && in_array($in['person_type'], ['patient', 'proche'], true)) {
            $role = $in['person_type'];
        } else {
            return ['account' => 'Choisissez un type de compte valide.'];
        }

        if (($m = Validator::name($in['first_name'], 'Prénom')) !== null) {
            $errors['first_name'] = $m;
        }
        if (($m = Validator::name($in['last_name'], 'Nom')) !== null) {
            $errors['last_name'] = $m;
        }
        if (($m = Validator::email($in['email'])) !== null) {
            $errors['email'] = $m;
        }
        if (($m = Validator::phone($in['phone'])) !== null) {
            $errors['phone'] = $m;
        }
        if (($m = Validator::password($password)) !== null) {
            $errors['password'] = $m;
        } elseif ($password !== $confirm) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
        }
        if ($this->post('accept') !== '1') {
            $errors['accept'] = 'Vous devez accepter les conditions d\'utilisation.';
        }
        $errors += $this->validateRoleFields($role, $in);

        if ($errors !== []) {
            return $errors;
        }

        try {
            $this->db->beginTransaction();

            if ($this->emailExists($in['email'])) {
                $this->db->rollBack();
                return ['email' => 'Cette adresse e-mail est déjà utilisée.'];
            }

            $this->insertUser([
                'first_name' => $in['first_name'],
                'last_name' => $in['last_name'],
                'email' => $in['email'],
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $this->nullIfEmpty($in['phone']),
                'role' => $role,
                'status' => $role === 'medecin' ? 'inactive' : 'active',
            ], $in);

            $this->db->commit();
            $this->createdRole = $role;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Registration failed: ' . $e->getMessage());
            if ($e instanceof \PDOException && (int) ($e->errorInfo[1] ?? 0) === 1062) {
                return ['_form' => 'Cette adresse e-mail ou ce n° de licence est déjà enregistré.'];
            }
            return ['_form' => 'Une erreur est survenue pendant la création du compte. Réessayez.'];
        }

        return [];
    }

    public function logout(): void
    {
        if (!Security::isPost() || !$this->csrfValid()) {
            $this->redirectHome();
        }

        Security::destroySession();
        Security::restartSession();
        Flash::set('success', 'Vous avez été déconnecté.');
        $this->redirect('login');
    }

    public function forgotPassword(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirectHome();
        }

        $errors = [];
        $email = '';
        $sent = false;

        if (Security::isPost()) {
            $email = strtolower($this->post('email'));

            if (!$this->csrfValid()) {
                $errors['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } elseif (($m = Validator::email($email)) !== null) {
                $errors['email'] = $m;
            } else {
                $config = require __DIR__ . '/../../config.php';
                $now = time();
                $requests = array_values(array_filter(
                    is_array($_SESSION['reset_requests'] ?? null) ? $_SESSION['reset_requests'] : [],
                    fn($t) => ($now - (int) $t) < 900
                ));

                if (count($requests) >= (int) $config['reset_max_requests']) {
                    $errors['_form'] = 'Trop de demandes. Patientez quelques minutes avant de réessayer.';
                } else {
                    $requests[] = $now;
                    $_SESSION['reset_requests'] = $requests;
                    $this->createResetRequest($email, (int) $config['reset_lifetime_minutes']);
                    $sent = true;
                }
            }
        }

        $this->renderFront('forgot', [
            'errors' => $errors,
            'email' => $email,
            'sent' => $sent,
            'pageTitle' => 'Mot de passe oublié — VIE',
            'currentPage' => 'login',
        ]);
    }

    private function createResetRequest(string $email, int $minutes): void
    {
        $user = $this->findUserByEmail($email);
        if ($user === null || $user->getStatus() === 'blocked') {
            return;
        }

        $stmt = $this->db->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = :user_id AND used_at IS NULL');
        $stmt->execute(['user_id' => $user->getId()]);

        $token = bin2hex(random_bytes(32));
        $minutes = max(1, $minutes);
        $stmt = $this->db->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, NOW() + INTERVAL ' . $minutes . ' MINUTE)');
        $stmt->execute(['user_id' => $user->getId(), 'token_hash' => hash('sha256', $token)]);

        $link = $this->baseUrl() . '/index.php?page=reset-password&token=' . $token;
        $body = "Bonjour " . $user->getFirstName() . ",\n\n"
            . "Vous avez demandé la réinitialisation de votre mot de passe VIE.\n"
            . "Ouvrez ce lien (valable " . $minutes . " minutes, utilisable une seule fois) :\n\n"
            . $link . "\n\n"
            . "Si vous n'êtes pas à l'origine de cette demande, ignorez ce message.\n";
        Mailer::send($user->getEmail(), 'Réinitialisation de votre mot de passe VIE', $body);
    }

    private function baseUrl(): string
    {
        $config = require __DIR__ . '/../../config.php';
        if (!empty($config['app_url'])) {
            return rtrim((string) $config['app_url'], '/');
        }
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $dir = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
        return $scheme . '://' . $host . $dir;
    }

    private function findValidReset(string $token): ?array
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT id, user_id FROM password_resets WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW() LIMIT 1');
        $stmt->execute(['token_hash' => hash('sha256', $token)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function resetPassword(): void
    {
        if (Security::isAuthenticated()) {
            $this->redirectHome();
        }

        $token = Security::isPost() ? $this->post('token') : $this->get('token');
        $reset = $this->findValidReset($token);
        $errors = [];

        if ($reset !== null && Security::isPost()) {
            $password = $this->rawPost('password');
            $confirm = $this->rawPost('password_confirm');

            if (!$this->csrfValid()) {
                $errors['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } elseif (($m = Validator::password($password)) !== null) {
                $errors['password'] = $m;
            } elseif ($password !== $confirm) {
                $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
            } else {
                try {
                    $this->db->beginTransaction();
                    $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
                    $stmt->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $reset['user_id']]);
                    $stmt = $this->db->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = :user_id AND used_at IS NULL');
                    $stmt->execute(['user_id' => $reset['user_id']]);
                    $this->db->commit();
                } catch (\Throwable $e) {
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    error_log('Password reset failed: ' . $e->getMessage());
                    $errors['_form'] = 'Une erreur est survenue. Réessayez.';
                }

                if ($errors === []) {
                    Flash::set('success', 'Mot de passe modifié. Connectez-vous avec votre nouveau mot de passe.');
                    $this->redirect('login');
                }
            }
        }

        $this->renderFront('reset', [
            'errors' => $errors,
            'token' => $token,
            'validToken' => $reset !== null,
            'pageTitle' => 'Nouveau mot de passe — VIE',
            'currentPage' => 'login',
        ]);
    }
}
