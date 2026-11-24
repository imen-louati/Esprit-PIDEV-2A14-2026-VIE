<?php
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
                <p>Un oubli arrive. Retrouvez l'accès à votre espace en quelques minutes.</p>
                <footer>Informer, accompagner, orienter.</footer>
            </blockquote>
        </section>

        <section class="auth-panel">
            <a class="back-home" href="index.php?page=login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                Retour à la connexion
            </a>
            <div class="auth-card glass">
                <h1>Mot de passe oublié</h1>
                <p class="sub">Saisissez l'adresse e-mail de votre compte. Nous vous enverrons un lien de réinitialisation valable 30 minutes.</p>

                <?php if ($sent): ?>
                    <div class="alert alert-success" role="status">Si un compte existe pour cette adresse, un lien de réinitialisation vient d'être envoyé. Pensez à vérifier vos courriers indésirables.</div>
                <?php endif; ?>
                <?php if (isset($errors['_form'])): ?>
                    <div class="alert alert-error" role="alert"><?php echo e($errors['_form']); ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=forgot-password" data-validate novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <div class="field<?php echo hasError($errors, 'email'); ?>">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="nom@exemple.com" autocomplete="email" maxlength="255" value="<?php echo e($email); ?>" required>
                        <?php echo fieldError($errors, 'email'); ?>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Envoyer le lien</button>
                </form>

                <p class="auth-foot"><a href="index.php?page=login">Retour à la connexion</a></p>
            </div>
        </section>
    </main>
    <script src="assets/js/forms.js"></script>
</body>
</html>
