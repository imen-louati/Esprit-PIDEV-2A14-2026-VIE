<?php
$user = $user ?? ['name' => 'Invité', 'role' => 'admin'];
$role = $role ?? 'admin';
?>
            <header class="topbar">
                <button class="icon-btn menu-btn" type="button" id="menu-toggle" aria-label="Ouvrir le menu">
                    <svg class="ic"><use href="#i-menu"/></svg>
                </button>
                <form class="search" action="#" role="search">
                    <svg class="ic"><use href="#i-search"/></svg>
                    <input type="search" placeholder="Rechercher un patient, un rendez-vous, un article" aria-label="Rechercher">
                </form>
                <div class="topbar-right">
                    <button class="icon-btn has-dot" type="button" aria-label="Notifications">
                        <svg class="ic"><use href="#i-bell"/></svg>
                    </button>
                    <div class="profile">
                        <span class="avatar"><?php echo e(initials($user['name'])); ?></span>
                        <span class="profile-text">
                            <strong><?php echo e($user['name']); ?></strong>
                            <small><?php echo e(roleLabel($role)); ?></small>
                        </span>
                    </div>
                </div>
            </header>
