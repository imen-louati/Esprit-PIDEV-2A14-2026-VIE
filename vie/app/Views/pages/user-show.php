<?php
$name = $target->getFullName();
$isMe = $target->getId() === $user->getId();
?>
<div class="page-head">
    <div>
        <h1><?php echo e($name); ?></h1>
        <p><?php echo e(roleLabel($target->getRole())); ?> · inscrit le <?php echo e(formatDate($target->getCreatedAt())); ?></p>
    </div>
    <div class="page-actions">
        <a class="btn btn-ghost" href="index.php?page=users">Retour à la liste</a>
        <a class="btn btn-blue" href="index.php?page=users-edit&amp;id=<?php echo $target->getId(); ?>"><svg class="ic"><use href="#i-note"/></svg>Modifier</a>
        <?php if (!$isMe): ?>
            <form method="post" action="index.php?page=users-delete" data-confirm="Supprimer définitivement <?php echo e($name); ?> ? Cette action est irréversible.">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <input type="hidden" name="id" value="<?php echo $target->getId(); ?>">
                <button class="btn btn-danger" type="submit"><svg class="ic"><use href="#i-trash"/></svg>Supprimer</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid main-side">
    <section class="card">
        <div class="profile-head">
            <span class="avatar avatar-lg <?php echo e(avatarClass($target->getRole())); ?>"><?php echo e(initials($name)); ?></span>
            <div>
                <h2><?php echo e($name); ?></h2>
                <div class="tags">
                    <span class="badge <?php echo e(roleBadge($target->getRole())); ?>"><?php echo e(roleLabel($target->getRole())); ?></span>
                    <span class="badge <?php echo e(statusBadge($target->getStatus())); ?>"><?php echo e(statusLabel($target->getStatus())); ?></span>
                </div>
            </div>
        </div>
        <dl class="kv kv-wide">
            <div><dt>Identifiant</dt><dd>#<?php echo $target->getId(); ?></dd></div>
            <div><dt>E-mail</dt><dd><?php echo e($target->getEmail()); ?></dd></div>
            <div><dt>Téléphone</dt><dd><?php echo e($target->getPhone() !== '' ? $target->getPhone() : '—'); ?></dd></div>
            <div><dt>Dernière connexion</dt><dd><?php echo e(formatDate($target->getLastLoginAt(), true)); ?></dd></div>

            <?php if ($target->getRole() === 'patient'): ?>
                <div><dt>Date de naissance</dt><dd><?php echo e(formatDate($target->getBirthDate())); ?></dd></div>
                <div><dt>Type de cancer</dt><dd><?php echo e($target->getCancerType() !== '' ? $target->getCancerType() : '—'); ?></dd></div>
                <div><dt>Stade</dt><dd><?php echo e($target->getStage() !== '' ? $target->getStage() : '—'); ?></dd></div>
                <div><dt>Code de partage</dt><dd><?php echo e($target->getShareCode() !== '' ? $target->getShareCode() : '—'); ?></dd></div>
                <div><dt>Avancement</dt><dd><?php echo e($target->getProgress() !== '' ? $target->getProgress() : '—'); ?></dd></div>
            <?php elseif ($target->getRole() === 'medecin'): ?>
                <div><dt>Spécialité</dt><dd><?php echo e($target->getSpeciality()); ?></dd></div>
                <div><dt>N° de licence</dt><dd><?php echo e($target->getLicenseNumber()); ?></dd></div>
                <div><dt>Ville</dt><dd><?php echo e($target->getCity()); ?></dd></div>
            <?php elseif ($target->getRole() === 'proche'): ?>
                <div><dt>Patient lié</dt><dd><?php echo $patient !== null ? e($patient->getFullName()) : 'Aucun patient lié'; ?></dd></div>
                <div><dt>Lien</dt><dd><?php echo e($target->getRelationship() !== '' ? $target->getRelationship() : '—'); ?></dd></div>
                <div><dt>Partage</dt><dd>
                    <?php echo $target->isShareAppointments() ? 'Rendez-vous · ' : ''; ?>
                    <?php echo $target->isShareMedications() ? 'Médicaments · ' : ''; ?>
                    <?php echo $target->isShareDocuments() ? 'Documents' : ''; ?>
                    <?php echo (!$target->isShareAppointments() && !$target->isShareMedications() && !$target->isShareDocuments()) ? 'Rien de partagé' : ''; ?>
                </dd></div>
                <div><dt>Accès jusqu'au</dt><dd><?php echo e($target->getAccessExpiresAt() !== '' ? formatDate($target->getAccessExpiresAt()) : 'Sans limite'); ?></dd></div>
                <div><dt>État de l'accès</dt><dd><?php echo $target->getAccessRevokedAt() !== '' ? 'Révoqué le ' . e(formatDate($target->getAccessRevokedAt())) : 'Autorisé'; ?></dd></div>
            <?php endif; ?>
        </dl>
    </section>

    <div class="stack">
        <?php if ($target->getStatus() === 'inactive'): ?>
            <section class="card">
                <div class="card-head"><h2>Validation requise</h2></div>
                <p class="muted">Ce compte est inactif<?php echo $target->getRole() === 'medecin' ? ' : le profil médecin attend votre validation' : ''; ?>.</p>
                <form method="post" action="index.php?page=users-status" class="mt">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <input type="hidden" name="id" value="<?php echo $target->getId(); ?>">
                    <input type="hidden" name="do" value="validate">
                    <button class="btn btn-ok btn-block" type="submit">Valider le compte</button>
                </form>
            </section>
        <?php elseif ($target->getStatus() === 'blocked'): ?>
            <section class="card">
                <div class="card-head"><h2>Compte bloqué</h2></div>
                <form method="post" action="index.php?page=users-status">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <input type="hidden" name="id" value="<?php echo $target->getId(); ?>">
                    <input type="hidden" name="do" value="unblock">
                    <button class="btn btn-ok btn-block" type="submit">Débloquer le compte</button>
                </form>
            </section>
        <?php elseif (!$isMe): ?>
            <section class="card">
                <div class="card-head"><h2>Accès</h2></div>
                <form method="post" action="index.php?page=users-status" data-confirm="Bloquer le compte de <?php echo e($name); ?> ?">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <input type="hidden" name="id" value="<?php echo $target->getId(); ?>">
                    <input type="hidden" name="do" value="block">
                    <button class="btn btn-danger btn-block" type="submit">Bloquer le compte</button>
                </form>
            </section>
        <?php endif; ?>

        <?php if ($target->getRole() === 'patient'): ?>
            <section class="card">
                <div class="card-head"><h2>Proches liés</h2><span class="badge b-blue"><?php echo count($linked); ?></span></div>
                <?php if ($linked === []): ?>
                    <div class="empty-inline">Aucun proche n'est lié à ce patient.</div>
                <?php else: ?>
                    <div class="mini-list">
                        <?php foreach ($linked as $proche): ?>
                            <?php $pn = $proche['first_name'] . ' ' . $proche['last_name']; ?>
                            <div class="mini">
                                <span class="avatar av-blue"><?php echo e(initials($pn)); ?></span>
                                <div class="grow"><strong><?php echo e($pn); ?></strong><small><?php echo e($proche['relationship']); ?><?php echo $proche['access_revoked_at'] !== null ? ' · accès révoqué' : ''; ?></small></div>
                                <a class="btn btn-sm btn-soft" href="index.php?page=users-show&amp;id=<?php echo (int) $proche['id']; ?>">Voir</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</div>
