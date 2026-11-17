<?php
if (!isset($currentPage)) {
    $currentPage = '';
}
function navActive($page, $currentPage)
{
    return $page === $currentPage ? 'active' : '';
}
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
            <li><a class="btn btn-primary btn-sm <?php echo navActive('login', $currentPage); ?>" href="login.php">Connexion</a></li>
        </ul>
    </nav>
</header>
