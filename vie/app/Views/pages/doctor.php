<?php
$fullName = $user->getFullName();
?>
<div class="banner">
    <div>
        <h1>Bonjour, <?php echo e($fullName); ?></h1>
        <p><?php echo e($user->getSpeciality()); ?> · <?php echo e($user->getCity()); ?></p>
        <div class="banner-visits">
            <span>Visits for Today :</span>
            <strong>0</strong>
        </div>
    </div>
    <div class="banner-stats">
        <div><small>Patients actifs</small><b><?php echo (int) $stats['patients']; ?></b></div>
        <div><small>Nouveaux (7 j)</small><b><?php echo (int) $stats['newPatients']; ?></b></div>
        <div><small>Médecins</small><b><?php echo (int) $stats['doctors']; ?></b></div>
    </div>
</div>

<div class="grid main-side mt">
    <div class="stack">
        <section class="card">
            <div class="card-head">
                <div><h2>Patients du jour</h2><p><?php echo e(formatDate(date('Y-m-d'))); ?></p></div>
            </div>
            <div class="empty-inline">Aucun rendez-vous aujourd'hui. L'agenda de consultations sera branché par le module rendez-vous.</div>
        </section>

        <section class="card">
            <div class="card-head">
                <div><h2>Mon profil professionnel</h2><p>Informations vérifiées par l'administration</p></div>
                <a class="link" href="index.php?page=profile">Modifier</a>
            </div>
            <dl class="kv kv-wide">
                <div><dt>Nom</dt><dd><?php echo e($fullName); ?></dd></div>
                <div><dt>Spécialité</dt><dd><?php echo e($user->getSpeciality()); ?></dd></div>
                <div><dt>N° de licence</dt><dd><?php echo e($user->getLicenseNumber()); ?></dd></div>
                <div><dt>Ville</dt><dd><?php echo e($user->getCity()); ?></dd></div>
                <div><dt>E-mail</dt><dd><?php echo e($user->getEmail()); ?></dd></div>
                <div><dt>Téléphone</dt><dd><?php echo e($user->getPhone() !== '' ? $user->getPhone() : '—'); ?></dd></div>
            </dl>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="calendar-head">
                <h3><?php echo e($calendar['label']); ?></h3>
            </div>
            <div class="calendar" role="grid" aria-label="Calendrier du mois">
                <?php foreach (['L', 'M', 'M', 'J', 'V', 'S', 'D'] as $dow): ?>
                    <span class="dow"><?php echo e($dow); ?></span>
                <?php endforeach; ?>
                <?php foreach ($calendar['cells'] as $cell): ?>
                    <span class="<?php echo e($cell['class']); ?>"><?php echo (int) $cell['day']; ?></span>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="card">
            <div class="card-head"><h2>Prochains rendez-vous</h2></div>
            <div class="empty-inline">Aucun rendez-vous planifié.</div>
        </section>
    </div>
</div>
