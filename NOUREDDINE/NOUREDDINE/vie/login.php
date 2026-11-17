<?php
session_start();
require_once 'includes/db.php';

$pageTitle = 'Connexion — VIE';
$currentPage = 'login';
$error = '';
$account = 'personne';
$email = '';

$redirects = [
    'admin' => 'index.php?page=dashboard',
    'medecin' => 'index.php?page=doctor',
    'patient' => 'index.php?page=patient',
    'proche' => 'index.php?page=proche',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $account = $_POST['account'] ?? 'personne';

    if ($email === '' || $password === '') {
        $error = 'Renseignez votre adresse e-mail et votre mot de passe.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id, full_name, email, password, role, status FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            $allowed = [
                'personne' => ['patient', 'proche'],
                'medecin' => ['medecin'],
                'admin' => ['admin'],
            ];

            if (!$user || !password_verify($password, $user['password'])) {
                $error = 'Adresse e-mail ou mot de passe incorrect.';
            } elseif (!in_array($user['role'], $allowed[$account] ?? [], true)) {
                $error = 'Ce compte ne correspond pas au type de connexion choisi.';
            } elseif ($user['status'] === 'en_attente') {
                $error = 'Votre compte est en attente de validation par l\'équipe VIE.';
            } elseif ($user['status'] === 'suspendu') {
                $error = 'Votre compte est suspendu. Contactez le support.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => (int) $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                db()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')->execute(['id' => $user['id']]);
                db()->prepare('INSERT INTO audit_logs (user_id, action, target, ip_address) VALUES (:id, :action, :target, :ip)')
                    ->execute(['id' => $user['id'], 'action' => 'connexion', 'target' => $user['email'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? null]);
                header('Location: ' . $redirects[$user['role']]);
                exit;
            }
        } catch (PDOException $e) {
            $error = 'La base de données est inaccessible. Vérifiez config.php et importez database/database.sql.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>
    <div class="page-bg" aria-hidden="true"></div>
    <main class="auth">
        <section class="auth-art">
            <a href="home.php" aria-label="Retour à l'accueil"><img src="assets/img/logo-vie-clair.png" alt="VIE"></a>
            <blockquote>
                <p>Un parcours de santé plus simple, plus accessible et mieux coordonné.</p>
                <footer>Informer, accompagner, orienter.</footer>
            </blockquote>
        </section>

        <section class="auth-panel">
            <a class="back-home" href="home.php">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                Retour au site
            </a>
            <div class="auth-card glass" data-auth data-initial="<?php echo $account === 'medecin' ? 'medecin' : 'personne'; ?>">
                <h1 data-title>Content de vous revoir</h1>
                <p class="sub" data-sub>Accédez à votre suivi, vos rendez-vous et vos documents.</p>

                <div class="toggle" data-active="personne" role="group" aria-label="Type de compte">
                    <span class="thumb" aria-hidden="true"></span>
                    <button type="button" class="is-active" data-mode="personne" aria-pressed="true">Patient / Proche</button>
                    <button type="button" data-mode="medecin" aria-pressed="false">Médecin</button>
                </div>

                <?php if ($error !== ''): ?>
                    <div class="alert" role="alert"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <input type="hidden" name="account" value="<?php echo htmlspecialchars($account); ?>">
                    <div class="field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="nom@exemple.com" autocomplete="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Votre mot de passe" autocomplete="current-password" required>
                    </div>
                    <div class="form-row">
                        <label><input type="checkbox" name="remember" value="1"> Rester connecté</label>
                        <a href="#">Mot de passe oublié ?</a>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
                </form>

                <p class="auth-foot">Pas encore de compte ? <a href="login.php">Créer mon espace</a><br>
                    <a href="#" data-admin>Accès administration</a>
                </p>
            </div>
        </section>
    </main>
    <?php require_once 'includes/scripts.php'; ?>
</body>
</html>
