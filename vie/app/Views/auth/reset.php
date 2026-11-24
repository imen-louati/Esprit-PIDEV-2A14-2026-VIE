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
                <p>Choisissez un nouveau mot de passe solide pour protéger votre espace.</p>
                <footer>Informer, accompagner, orienter.</footer>
            </blockquote>
        </section>

        <section class="auth-panel">
            <div class="auth-card glass">
                <?php if (!$validToken): ?>
                    <h1>Lien invalide</h1>
                    <p class="sub">Ce lien de réinitialisation est invalide, expiré ou a déjà été utilisé.</p>
                    <a class="btn btn-primary btn-block" href="index.php?page=forgot-password">Demander un nouveau lien</a>
                <?php else: ?>
                    <h1>Nouveau mot de passe</h1>
                    <p class="sub">Choisissez un mot de passe d'au moins 8 caractères avec une majuscule, une minuscule et un chiffre.</p>

                    <?php if (isset($errors['_form'])): ?>
                        <div class="alert alert-error" role="alert"><?php echo e($errors['_form']); ?></div>
                    <?php endif; ?>

                    <form method="post" action="index.php?page=reset-password" data-validate novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                        <div class="field<?php echo hasError($errors, 'password'); ?>">
                            <label for="password">Nouveau mot de passe</label>
                            <div class="pw-wrap">
                                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" data-rule="password" required>
                                <button class="pw-toggle" type="button" data-toggle-password="#password" aria-label="Afficher le mot de passe">Afficher</button>
                            </div>
                            <div class="strength" data-strength-for="#password" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                            <?php echo fieldError($errors, 'password'); ?>
                        </div>
                        <div class="field<?php echo hasError($errors, 'password_confirm'); ?>">
                            <label for="password_confirm">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" maxlength="72" data-match="#password" required>
                            <?php echo fieldError($errors, 'password_confirm'); ?>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">Enregistrer le mot de passe</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="assets/js/forms.js"></script>
</body>
</html>
