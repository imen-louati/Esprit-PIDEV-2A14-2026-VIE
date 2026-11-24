<?php
$fullName = $user->getFullName();
?>
<?php if ($state === 'none'): ?>
    <section class="empty-state card">
        <div class="empty-icon"><svg class="ic"><use href="#i-users"/></svg></div>
        <h1>Aucun patient lié</h1>
        <p>Votre compte n'est lié à aucun patient (le compte du patient a peut-être été supprimé). Contactez l'équipe VIE.</p>
    </section>
<?php elseif ($state === 'revoked' || $state === 'expired'): ?>
    <section class="empty-state card">
        <div class="empty-icon"><svg class="ic"><use href="#i-lock"/></svg></div>
        <h1><?php echo $state === 'revoked' ? 'Accès révoqué' : 'Accès expiré'; ?></h1>
        <p><?php echo e($patient->getFullName()); ?> <?php echo $state === 'revoked' ? 'a révoqué' : 'avait limité'; ?> votre accès au suivi. Demandez-lui de vous le rétablir depuis son espace.</p>
    </section>
<?php else: ?>
    <div class="notice notice-readonly">
        <div class="notice-icon"><svg class="ic"><use href="#i-lock"/></svg></div>
        <div>
            <strong>Vous consultez le suivi de <?php echo e($patient->getFullName()); ?></strong>
            <span>Accès en lecture seule<?php echo $user->getAccessExpiresAt() !== '' ? ' jusqu\'au ' . e(formatDate($user->getAccessExpiresAt())) : ''; ?>. Vous ne voyez que ce que le patient a choisi de partager.</span>
        </div>
        <span class="badge b-violet">Lecture seule</span>
    </div>

    <div class="page-head">
        <div>
            <h1>Bonjour, <?php echo e($user->getFirstName()); ?></h1>
            <p>Résumé du suivi de <?php echo e($patient->getFullName()); ?> · <?php echo e($user->getRelationship()); ?></p>
        </div>
    </div>

    <div class="grid cols-4">
        <article class="card stat"><div class="stat-icon i-pink"><svg class="ic"><use href="#i-heart"/></svg></div><div><small>Type</small><strong class="stat-text"><?php echo e($patient->getCancerType() !== '' ? $patient->getCancerType() : '—'); ?></strong></div></article>
        <article class="card stat"><div class="stat-icon i-violet"><svg class="ic"><use href="#i-pulse"/></svg></div><div><small>Stade</small><strong class="stat-text"><?php echo e($patient->getStage() !== '' ? $patient->getStage() : '—'); ?></strong></div></article>
        <article class="card stat"><div class="stat-icon i-blue"><svg class="ic"><use href="#i-user"/></svg></div><div><small>Lien</small><strong class="stat-text"><?php echo e($user->getRelationship()); ?></strong></div></article>
        <article class="card stat"><div class="stat-icon i-green"><svg class="ic"><use href="#i-clock"/></svg></div><div><small>Accès</small><strong class="stat-text"><?php echo $daysLeft !== null ? (int) $daysLeft . ' j' : 'Illimité'; ?></strong></div></article>
    </div>

    <div class="grid main-side mt">
        <div class="stack">
            <section class="card">
                <div class="card-head"><h2>Avancement du suivi</h2></div>
                <p><?php echo e($patient->getProgress() !== '' ? $patient->getProgress() : 'Le patient n\'a pas encore renseigné son état d\'avancement.'); ?></p>
            </section>

            <section class="card">
                <div class="card-head"><h2>Rendez-vous</h2><?php echo $user->isShareAppointments() ? '<span class="badge b-green">Partagé</span>' : '<span class="badge b-gray">Non partagé</span>'; ?></div>
                <?php if ($user->isShareAppointments()): ?>
                    <div class="empty-inline">Aucun rendez-vous planifié pour le moment.</div>
                <?php else: ?>
                    <div class="empty-inline">Le patient ne partage pas ses rendez-vous.</div>
                <?php endif; ?>
            </section>

            <section class="card">
                <div class="card-head"><h2>Médicaments</h2><?php echo $user->isShareMedications() ? '<span class="badge b-green">Partagé</span>' : '<span class="badge b-gray">Non partagé</span>'; ?></div>
                <?php if ($user->isShareMedications()): ?>
                    <div class="empty-inline">Aucun médicament enregistré pour le moment.</div>
                <?php else: ?>
                    <div class="empty-inline">Le patient ne partage pas ses médicaments.</div>
                <?php endif; ?>
            </section>
        </div>

        <div class="stack">
            <section class="card">
                <div class="card-head"><h2>Ce que le patient partage</h2></div>
                <div>
                    <div class="perm"><div class="<?php echo $user->isShareAppointments() ? 'perm-yes' : 'perm-no'; ?>"><svg class="ic"><use href="#<?php echo $user->isShareAppointments() ? 'i-check' : 'i-x'; ?>"/></svg></div><div class="grow"><strong>Rendez-vous</strong><small>Dates, heures et lieux</small></div></div>
                    <div class="perm"><div class="<?php echo $user->isShareMedications() ? 'perm-yes' : 'perm-no'; ?>"><svg class="ic"><use href="#<?php echo $user->isShareMedications() ? 'i-check' : 'i-x'; ?>"/></svg></div><div class="grow"><strong>Médicaments</strong><small>Noms, doses et horaires</small></div></div>
                    <div class="perm"><div class="<?php echo $user->isShareDocuments() ? 'perm-yes' : 'perm-no'; ?>"><svg class="ic"><use href="#<?php echo $user->isShareDocuments() ? 'i-check' : 'i-x'; ?>"/></svg></div><div class="grow"><strong>Documents médicaux</strong><small>Comptes rendus et analyses</small></div></div>
                </div>
            </section>

            <section class="card">
                <div class="card-head"><h2>Mon compte</h2><a class="link" href="index.php?page=profile">Profil</a></div>
                <dl class="kv kv-wide">
                    <div><dt>Nom</dt><dd><?php echo e($fullName); ?></dd></div>
                    <div><dt>E-mail</dt><dd><?php echo e($user->getEmail()); ?></dd></div>
                </dl>
            </section>
        </div>
    </div>
<?php endif; ?>
