<?php
$fullName = $user->getFullName();
?>
<?php if ($activeProche !== null): ?>
    <div class="notice notice-pink">
        <div class="notice-icon"><svg class="ic"><use href="#i-shield"/></svg></div>
        <div>
            <strong>Proche autorisé : <?php echo e($activeProche['first_name'] . ' ' . $activeProche['last_name']); ?></strong>
            <span>Il consulte votre résumé de suivi<?php echo $activeProche['access_expires_at'] !== null ? ' jusqu\'au ' . e(formatDate($activeProche['access_expires_at'])) : ' sans limite de durée'; ?>. Vous gardez le contrôle ci-dessous.</span>
        </div>
        <a class="btn btn-sm btn-ghost" href="#proches">Gérer les autorisations</a>
    </div>
<?php endif; ?>

<div class="banner banner-patient">
    <div>
        <h1>Bonjour, <?php echo e($user->getFirstName()); ?></h1>
        <p>Votre espace de suivi personnel VIE.</p>
        <div class="banner-visits">
            <span>Mon code de partage</span>
            <strong class="banner-code"><?php echo e($user->getShareCode()); ?></strong>
        </div>
    </div>
    <div class="banner-stats">
        <div><small>Type</small><b><?php echo e($user->getCancerType() !== '' ? $user->getCancerType() : '—'); ?></b></div>
        <div><small>Stade</small><b><?php echo e($user->getStage() !== '' ? $user->getStage() : '—'); ?></b></div>
        <div><small>Proches</small><b><?php echo count($proches); ?></b></div>
    </div>
</div>

<div class="grid main-side mt">
    <div class="stack">
        <section class="card">
            <div class="card-head">
                <div><h2>Mon dossier</h2><p>Informations saisies à l'inscription</p></div>
                <a class="link" href="index.php?page=profile">Modifier</a>
            </div>
            <dl class="kv kv-wide">
                <div><dt>Nom</dt><dd><?php echo e($fullName); ?></dd></div>
                <div><dt>Âge</dt><dd><?php echo $age !== null ? (int) $age . ' ans (' . e(formatDate($user->getBirthDate())) . ')' : '—'; ?></dd></div>
                <div><dt>Type de cancer</dt><dd><?php echo e($user->getCancerType() !== '' ? $user->getCancerType() : '—'); ?></dd></div>
                <div><dt>Stade</dt><dd><?php echo e($user->getStage() !== '' ? $user->getStage() : '—'); ?></dd></div>
                <div><dt>Avancement</dt><dd><?php echo e($user->getProgress() !== '' ? $user->getProgress() : 'Non renseigné'); ?></dd></div>
            </dl>
        </section>

        <section class="card" id="proches">
            <div class="card-head">
                <div><h2>Mes proches autorisés</h2><p>Vous décidez de ce que chaque proche peut voir.</p></div>
                <span class="badge b-blue"><?php echo count($proches); ?></span>
            </div>
            <?php if ($proches === []): ?>
                <div class="empty-inline">Aucun proche n'est lié à votre compte. Donnez votre code <strong><?php echo e($user->getShareCode()); ?></strong> à un proche : il l'utilisera à son inscription.</div>
            <?php else: ?>
                <div class="access-list">
                    <?php foreach ($proches as $proche): ?>
                        <?php
                        $pn = $proche['first_name'] . ' ' . $proche['last_name'];
                        $revoked = $proche['access_revoked_at'] !== null;
                        $expired = $proche['access_expires_at'] !== null && $proche['access_expires_at'] < $today;
                        ?>
                        <article class="access-item">
                            <div class="mini">
                                <span class="avatar av-blue"><?php echo e(initials($pn)); ?></span>
                                <div class="grow"><strong><?php echo e($pn); ?></strong><small><?php echo e($proche['relationship']); ?> · <?php echo e($proche['email']); ?></small></div>
                                <?php if ($revoked): ?>
                                    <span class="badge b-red">Révoqué</span>
                                <?php elseif ($expired): ?>
                                    <span class="badge b-amber">Expiré</span>
                                <?php else: ?>
                                    <span class="badge b-green">Autorisé</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="index.php?page=patient-access" class="access-form">
                                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                <input type="hidden" name="proche_id" value="<?php echo (int) $proche['id']; ?>">
                                <label class="perm-line"><span>Rendez-vous</span><span class="switch"><input type="checkbox" name="share_appointments" value="1" <?php echo (int) $proche['share_appointments'] === 1 ? 'checked' : ''; ?>><span></span></span></label>
                                <label class="perm-line"><span>Médicaments</span><span class="switch"><input type="checkbox" name="share_medications" value="1" <?php echo (int) $proche['share_medications'] === 1 ? 'checked' : ''; ?>><span></span></span></label>
                                <label class="perm-line"><span>Documents</span><span class="switch"><input type="checkbox" name="share_documents" value="1" <?php echo (int) $proche['share_documents'] === 1 ? 'checked' : ''; ?>><span></span></span></label>
                                <div class="field">
                                    <label for="exp-<?php echo (int) $proche['id']; ?>">Accès valable jusqu'au <span class="optional">(vide = sans limite)</span></label>
                                    <input id="exp-<?php echo (int) $proche['id']; ?>" type="date" name="access_expires_at" min="<?php echo e($today); ?>" value="<?php echo e((string) $proche['access_expires_at']); ?>">
                                </div>
                                <button class="btn btn-sm btn-blue" type="submit">Enregistrer</button>
                            </form>
                            <form method="post" action="index.php?page=patient-access-revoke" data-confirm="<?php echo $revoked ? 'Rétablir l\'accès de ' . e($pn) . ' ?' : 'Révoquer l\'accès de ' . e($pn) . ' ?'; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                <input type="hidden" name="proche_id" value="<?php echo (int) $proche['id']; ?>">
                                <input type="hidden" name="do" value="<?php echo $revoked ? 'restore' : 'revoke'; ?>">
                                <button class="btn btn-sm <?php echo $revoked ? 'btn-ok' : 'btn-danger'; ?>" type="submit"><?php echo $revoked ? 'Rétablir l\'accès' : 'Révoquer l\'accès'; ?></button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="card-head"><h2>Code de partage</h2></div>
            <div class="code-box">
                <div><small>À donner à un proche</small><strong><?php echo e($user->getShareCode()); ?></strong></div>
                <button class="btn btn-sm btn-primary" type="button" data-action="copy" data-value="<?php echo e($user->getShareCode()); ?>"><svg class="ic"><use href="#i-copy"/></svg>Copier</button>
            </div>
            <p class="muted" style="margin-top:12px">Le proche saisit ce code à l'inscription pour être lié à votre compte.</p>
        </section>

        <section class="card">
            <div class="card-head"><h2>Prochain rendez-vous</h2></div>
            <div class="empty-inline">Aucun rendez-vous planifié.</div>
        </section>

        <section class="card">
            <div class="card-head"><h2>Mes médicaments</h2></div>
            <div class="empty-inline">Aucun médicament enregistré.</div>
        </section>
    </div>
</div>
