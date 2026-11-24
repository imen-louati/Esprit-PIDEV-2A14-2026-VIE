<?php
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>
        <div class="app-overlay"></div>
        <main class="app-main">
            <?php require_once __DIR__ . '/partials/header.php'; ?>

            <div class="app-content">
                <?php foreach ($flashes as $flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>" role="alert">
                        <span><?php echo e($flash['message']); ?></span>
                        <button class="alert-close" type="button" aria-label="Fermer">&times;</button>
                    </div>
                <?php endforeach; ?>

                <?php echo $content; ?>
            </div>

            <?php require_once __DIR__ . '/partials/footer.php'; ?>
        </main>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
