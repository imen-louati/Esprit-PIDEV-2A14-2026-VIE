<?php
if (!isset($currentPage)) {
    $currentPage = '';
}
$isLogged = \App\Core\Security::isAuthenticated();
$spacePage = \App\Core\Security::homePage((string) ($_SESSION['role'] ?? ''));
?>
<header class="nav-shell">
    <nav class="nav glass" aria-label="Navigation principale">
        <a class="brand" href="home.php" aria-label="VIE — accueil">
            <img src="assets/img/logo-vie-clair.png" alt="VIE">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="Ouvrir le menu">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a class="<?php echo navActive('home', $currentPage); ?>" href="home.php">Accueil</a></li>
            <li><a href="home.php#categories">Catégories</a></li>
            <li><a href="home.php#actualites">Actualités</a></li>
            <li><a href="home.php#evenements">Événements</a></li>
            <li><a href="home.php#espaces">Espaces</a></li>
            <?php if ($isLogged): ?>
                <li><a class="btn btn-primary btn-sm" href="index.php?page=<?php echo e($spacePage); ?>">Mon espace</a></li>
                <li>
                    <form class="nav-logout" method="post" action="index.php?page=logout">
                        <?php echo \App\Core\Security::csrfField(); ?>
                        <button class="btn btn-ghost btn-sm" type="submit">Déconnexion</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a class="btn btn-ghost btn-sm <?php echo navActive('login', $currentPage); ?>" href="index.php?page=login">Connexion</a></li>
                <li><a class="btn btn-primary btn-sm" href="index.php?page=register">Créer un compte</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
