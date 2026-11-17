<?php
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>
        <div class="app-overlay"></div>
        <main class="app-main">
            <?php require_once __DIR__ . '/partials/header.php'; ?>

            <div class="app-content">
                <?php echo $content; ?>
            </div>

            <?php require_once __DIR__ . '/partials/footer.php'; ?>
        </main>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
