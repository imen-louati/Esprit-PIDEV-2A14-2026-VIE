<?php

namespace App\Controllers;

use App\Config\Database;
use App\Core\Flash;
use App\Core\Security;
use App\Core\Validator;
use App\Models\UserModel;
use PDO;

abstract class Controller
{
    protected string $page = 'dashboard';
    protected string $title = 'VIE';
    protected array $allowedRoles = [];
    protected PDO $db;
    protected ?UserModel $user = null;

    public function __construct()
    {
        $this->db = Database::getConnexion();
        $this->authorize();
    }

    protected function authorize(): void
    {
        if ($this->allowedRoles === []) {
            return;
        }

        if (!Security::isAuthenticated()) {
            Flash::set('warning', 'Connectez-vous pour accéder à cet espace.');
            $this->redirect('login');
        }

        $user = $this->findUserById((int) $_SESSION['user_id']);

        if ($user === null || $user->getStatus() !== 'active') {
            Security::destroySession();
            Security::restartSession();
            Flash::set('error', 'Votre session n\'est plus valide. Reconnectez-vous.');
            $this->redirect('login');
        }

        $_SESSION['role'] = $user->getRole();
        $_SESSION['username'] = $user->getFullName();
        $this->user = $user;

        if (!in_array($user->getRole(), $this->allowedRoles, true)) {
            $this->forbidden();
        }
    }

    protected function forbidden(): void
    {
        http_response_code(403);
        $this->page = 'forbidden';
        $this->title = 'Accès refusé | VIE';
        $this->render('forbidden');
        exit;
    }

    protected function redirect(string $page, array $params = []): void
    {
        header('Location: index.php?' . http_build_query(['page' => $page] + $params));
        exit;
    }

    protected function redirectHome(): void
    {
        $role = (string) ($_SESSION['role'] ?? '');
        $this->redirect(Security::homePage($role));
    }

    protected function requirePost(): void
    {
        if (!Security::isPost()) {
            $this->redirectHome();
        }
    }

    protected function csrfValid(): bool
    {
        $token = $_POST['csrf_token'] ?? null;
        return Security::verifyCsrf(is_string($token) ? $token : null);
    }

    protected function post(string $key): string
    {
        $value = $_POST[$key] ?? '';
        return is_string($value) ? trim($value) : '';
    }

    protected function rawPost(string $key): string
    {
        $value = $_POST[$key] ?? '';
        return is_string($value) ? $value : '';
    }

    protected function get(string $key): string
    {
        $value = $_GET[$key] ?? '';
        return is_string($value) ? trim($value) : '';
    }

    protected function capture(string $file, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $role = $this->user !== null ? $this->user->getRole() : '';
        $csrfToken = Security::csrfToken();
        $shared = [
            'user' => $this->user,
            'role' => $role,
            'csrfToken' => $csrfToken,
            'currentPage' => $this->page,
        ];

        $content = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data + $shared);
        $extraScripts = $scriptsView !== null
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data + $shared)
            : '';

        $title = $this->title;
        $currentPage = $this->page;
        $user = $this->user;
        $flashes = Flash::pull();

        require __DIR__ . '/../Views/layout.php';
    }

    protected function renderFront(string $view, array $data = []): void
    {
        $data += [
            'csrfToken' => Security::csrfToken(),
            'flashes' => Flash::pull(),
            'currentPage' => $this->page,
            'pageTitle' => $this->title,
        ];
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/auth/' . $view . '.php';
    }

    protected function findUserById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    protected function findUserByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    protected function emailExists(string $email, int $ignoreId = 0): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1');
        $stmt->execute(['email' => $email, 'id' => $ignoreId]);
        return (bool) $stmt->fetch();
    }

    protected function licenseExists(string $license, int $ignoreId = 0): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE license_number = :license AND id <> :id LIMIT 1');
        $stmt->execute(['license' => $license, 'id' => $ignoreId]);
        return (bool) $stmt->fetch();
    }

    protected function countUsers(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    protected function countOtherActiveAdmins(int $excludeId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin' AND status = 'active' AND id <> :id");
        $stmt->execute(['id' => $excludeId]);
        return (int) $stmt->fetchColumn();
    }

    protected function findPatientIdByCode(string $code): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE role = 'patient' AND status = 'active' AND share_code = :code LIMIT 1");
        $stmt->execute(['code' => $code]);
        $id = $stmt->fetchColumn();
        return $id === false ? null : (int) $id;
    }

    protected function generateShareCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        for ($try = 0; $try < 20; $try++) {
            $code = 'CC-';
            for ($i = 0; $i < 4; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $code .= '-' . random_int(10, 99);

            $stmt = $this->db->prepare('SELECT id FROM users WHERE share_code = :code LIMIT 1');
            $stmt->execute(['code' => $code]);
            if (!$stmt->fetch()) {
                return $code;
            }
        }
        throw new \RuntimeException('Impossible de générer un code de partage unique.');
    }

    protected function nullIfEmpty(string $value): ?string
    {
        return $value === '' ? null : $value;
    }

    protected function validateRoleFields(string $role, array $in, int $ignoreUserId = 0): array
    {
        $errors = [];

        if ($role === 'patient') {
            if (($m = Validator::birthDate($in['birth_date'])) !== null) {
                $errors['birth_date'] = $m;
            }
            if (($m = Validator::inList($in['cancer_type'], Validator::CANCER_TYPES, 'Choisissez un type de cancer.')) !== null) {
                $errors['cancer_type'] = $m;
            }
            if (($m = Validator::inList($in['stage'], Validator::STAGES, 'Choisissez un stade.')) !== null) {
                $errors['stage'] = $m;
            }
            if (($m = Validator::progress($in['progress'])) !== null) {
                $errors['progress'] = $m;
            }
        } elseif ($role === 'medecin') {
            if (($m = Validator::inList($in['speciality'], Validator::SPECIALITIES, 'Choisissez une spécialité dans la liste.')) !== null) {
                $errors['speciality'] = $m;
            }
            if (($m = Validator::licenseNumber($in['license_number'])) !== null) {
                $errors['license_number'] = $m;
            } elseif ($this->licenseExists($in['license_number'], $ignoreUserId)) {
                $errors['license_number'] = 'Ce n° de licence est déjà enregistré.';
            }
            if (($m = Validator::city($in['city'])) !== null) {
                $errors['city'] = $m;
            }
        } elseif ($role === 'proche') {
            if (($m = Validator::shareCode($in['patient_code'])) !== null) {
                $errors['patient_code'] = $m;
            } elseif ($this->findPatientIdByCode($in['patient_code']) === null) {
                $errors['patient_code'] = 'Aucun patient actif ne correspond à ce code.';
            }
            if (($m = Validator::inList($in['relationship'], Validator::RELATIONSHIPS, 'Choisissez votre lien avec le patient.')) !== null) {
                $errors['relationship'] = $m;
            }
        }

        return $errors;
    }

    protected function roleData(string $role, array $in): array
    {
        $data = [
            'birth_date' => null,
            'cancer_type' => null,
            'stage' => null,
            'progress' => null,
            'speciality' => null,
            'license_number' => null,
            'city' => null,
            'patient_id' => null,
            'relationship' => null,
        ];

        if ($role === 'patient') {
            $data['birth_date'] = $this->nullIfEmpty($in['birth_date']);
            $data['cancer_type'] = $this->nullIfEmpty($in['cancer_type']);
            $data['stage'] = $this->nullIfEmpty($in['stage']);
            $data['progress'] = $this->nullIfEmpty($in['progress']);
        } elseif ($role === 'medecin') {
            $data['speciality'] = $this->nullIfEmpty($in['speciality']);
            $data['license_number'] = $this->nullIfEmpty($in['license_number']);
            $data['city'] = $this->nullIfEmpty($in['city']);
        } elseif ($role === 'proche') {
            $data['patient_id'] = $this->findPatientIdByCode($in['patient_code']);
            $data['relationship'] = $this->nullIfEmpty($in['relationship']);
        }

        return $data;
    }

    protected function authDefaults(string $role): array
    {
        $isProche = $role === 'proche';
        return [
            'share_code' => $role === 'patient' ? $this->generateShareCode() : null,
            'share_appointments' => $isProche ? 1 : null,
            'share_medications' => $isProche ? 1 : null,
            'share_documents' => $isProche ? 0 : null,
            'access_expires_at' => null,
            'access_revoked_at' => null,
        ];
    }

    protected function insertUser(array $common, array $in): int
    {
        $columns = array_merge($common, $this->roleData($common['role'], $in), $this->authDefaults($common['role']));
        $names = array_keys($columns);
        $sql = 'INSERT INTO users (' . implode(', ', $names) . ') VALUES (:' . implode(', :', $names) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($columns);
        return (int) $this->db->lastInsertId();
    }

    protected function updateUser(UserModel $existing, array $common, array $in): void
    {
        $role = $common['role'];
        $columns = array_merge($common, $this->roleData($role, $in));

        if ($existing->getRole() !== $role) {
            $columns = array_merge($columns, $this->authDefaults($role));
            if ($existing->getRole() === 'patient') {
                $stmt = $this->db->prepare('UPDATE users SET patient_id = NULL WHERE patient_id = :id');
                $stmt->execute(['id' => $existing->getId()]);
            }
        }

        $assignments = [];
        foreach (array_keys($columns) as $name) {
            $assignments[] = $name . ' = :' . $name;
        }
        $columns['id'] = $existing->getId();
        $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $assignments) . ' WHERE id = :id');
        $stmt->execute($columns);
    }
}
