<?php
$old = $old ?? ['email' => ''];
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once __DIR__ . '/../../../includes/head.php'; ?>
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
            <div class="auth-card glass">
                <h1>Content de vous revoir</h1>
                <p class="sub">Connectez-vous pour accéder à votre espace. Vous serez dirigé automatiquement vers l'interface correspondant à votre rôle.</p>

                <?php foreach ($flashes as $flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>" role="alert"><?php echo e($flash['message']); ?></div>
                <?php endforeach; ?>
                <?php if (isset($errors['_form'])): ?>
                    <div class="alert alert-error" role="alert"><?php echo e($errors['_form']); ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=login" data-validate novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <div class="field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="nom@exemple.com" autocomplete="email" maxlength="150" value="<?php echo e($old['email']); ?>" required>
                    </div>
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <div class="pw-wrap">
                            <input type="password" id="password" name="password" placeholder="Votre mot de passe" autocomplete="current-password" maxlength="72" required>
                            <button class="pw-toggle" type="button" data-toggle-password="#password" aria-label="Afficher le mot de passe">Afficher</button>
                        </div>
                    </div>
                    <p class="form-aside"><a href="index.php?page=forgot-password">Mot de passe oublié ?</a></p>
                    <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
                </form>

                <p class="auth-foot">Pas encore de compte ? <a href="index.php?page=register">Créer mon espace</a></p>
            </div>
        </section>
    </main>
    <script src="assets/js/forms.js"></script>
</body>
</html>
