<?php
$role = $role ?? '';
$fullName = $user !== null ? $user->getFullName() : '';
?>
            <header class="topbar">
                <button class="icon-btn menu-btn" type="button" id="menu-toggle" aria-label="Ouvrir le menu">
                    <svg class="ic"><use href="#i-menu"/></svg>
                </button>
                <?php if ($role === 'admin'): ?>
                    <form class="search" action="index.php" method="get" role="search">
                        <input type="hidden" name="page" value="users">
                        <svg class="ic"><use href="#i-search"/></svg>
                        <input type="search" name="q" maxlength="100" placeholder="Rechercher un utilisateur par nom ou e-mail" aria-label="Rechercher un utilisateur">
                    </form>
                <?php endif; ?>
                <div class="topbar-right">
                    <a class="profile" href="index.php?page=profile" title="Mon profil">
                        <span class="avatar <?php echo e(avatarClass($role)); ?>"><?php echo e(initials($fullName)); ?></span>
                        <span class="profile-text">
                            <strong><?php echo e($fullName); ?></strong>
                            <small><?php echo e(roleLabel($role)); ?></small>
                        </span>
                    </a>
                </div>
            </header>
