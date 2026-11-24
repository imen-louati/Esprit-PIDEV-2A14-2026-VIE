<?php
$currentPage = $currentPage ?? 'dashboard';
$role = $role ?? '';

$menus = [
    'admin' => [
        ['dashboard', 'Dashboard', 'i-grid'],
        ['users', 'Users', 'i-users'],
        ['profile', 'Profil', 'i-user'],
    ],
    'medecin' => [
        ['doctor', 'Dashboard', 'i-grid'],
        ['profile', 'Profil', 'i-user'],
    ],
    'patient' => [
        ['patient', 'Mon suivi', 'i-heart'],
        ['profile', 'Profil', 'i-user'],
    ],
    'proche' => [
        ['proche', 'Proche', 'i-users'],
        ['profile', 'Profil', 'i-user'],
    ],
];
$items = $menus[$role] ?? [];
$homeKey = $items !== [] ? $items[0][0] : 'login';
$activeKey = $currentPage;
if (in_array($currentPage, ['users-show', 'users-create', 'users-edit'], true)) {
    $activeKey = 'users';
}
?>
    <aside class="sidebar" aria-label="Navigation latérale">
        <a class="sidebar-logo" href="index.php?page=<?php echo e($homeKey); ?>" aria-label="VIE — accueil de l'espace">
            <img src="assets/img/logo-vie.png" alt="VIE">
        </a>
        <nav class="sidebar-nav">
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <a class="<?php echo navActive($item[0], $activeKey); ?>" href="index.php?page=<?php echo e($item[0]); ?>" title="<?php echo e($item[1]); ?>">
                            <svg class="ic"><use href="#<?php echo e($item[2]); ?>"/></svg>
                            <span><?php echo e($item[1]); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <form class="sidebar-out-form" method="post" action="index.php?page=logout">
            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
            <button class="sidebar-out" type="submit" title="Déconnexion">
                <svg class="ic"><use href="#i-logout"/></svg>
                <span>Quitter</span>
            </button>
        </form>
    </aside>
