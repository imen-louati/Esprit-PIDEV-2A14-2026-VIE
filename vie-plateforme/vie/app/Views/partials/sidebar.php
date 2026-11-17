<?php
$currentPage = $currentPage ?? 'dashboard';
$role = $role ?? 'admin';

$menus = [
    'admin' => [
        ['dashboard', 'Dashboard', 'i-grid'],
        ['users', 'Users', 'i-users'],
        ['centers', 'Map', 'i-map'],
        ['news', 'News', 'i-news'],
    ],
    'medecin' => [
        ['doctor', 'Dashboard', 'i-grid'],
        ['agenda', 'Agenda', 'i-calendar'],
    ],
    'patient' => [
        ['patient', 'Mon suivi', 'i-heart'],
    ],
    'proche' => [
        ['proche', 'Proche', 'i-users'],
    ],
];
$items = $menus[$role] ?? $menus['admin'];
?>
    <aside class="sidebar" aria-label="Navigation latérale">
        <a class="sidebar-logo" href="index.php?page=<?php echo e($items[0][0]); ?>" aria-label="VIE">
            <img src="assets/img/logo-vie.png" alt="VIE">
        </a>
        <nav class="sidebar-nav">
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <a class="<?php echo navActive($item[0], $currentPage); ?>" href="index.php?page=<?php echo e($item[0]); ?>" title="<?php echo e($item[1]); ?>">
                            <svg class="ic"><use href="#<?php echo e($item[2]); ?>"/></svg>
                            <span><?php echo e($item[1]); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <a class="sidebar-out" href="logout.php" title="Déconnexion">
            <svg class="ic"><use href="#i-logout"/></svg>
            <span>Quitter</span>
        </a>
    </aside>
