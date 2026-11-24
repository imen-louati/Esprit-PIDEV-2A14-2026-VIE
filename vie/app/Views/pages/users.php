<?php
$query = array_filter(['q' => $filters['q'], 'role' => $filters['role'], 'status' => $filters['status']], fn($v) => $v !== '');
$pageUrl = function (int $p) use ($query): string {
    return 'index.php?' . http_build_query(['page' => 'users'] + $query + ['p' => $p]);
};
?>
<div class="page-head">
    <div>
        <h1>Utilisateurs</h1>
        <p>Créer, consulter, modifier et supprimer les comptes de la plateforme</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="index.php?page=users-create"><svg class="ic"><use href="#i-plus"/></svg>Ajouter un utilisateur</a>
    </div>
</div>

<div class="grid cols-4">
    <article class="card stat"><div class="stat-icon i-pink"><svg class="ic"><use href="#i-heart"/></svg></div><div><small>Patients</small><strong><?php echo (int) $counts['patient']; ?></strong></div></article>
    <article class="card stat"><div class="stat-icon i-blue"><svg class="ic"><use href="#i-users"/></svg></div><div><small>Proches</small><strong><?php echo (int) $counts['proche']; ?></strong></div></article>
    <article class="card stat"><div class="stat-icon i-violet"><svg class="ic"><use href="#i-stetho"/></svg></div><div><small>Médecins</small><strong><?php echo (int) $counts['medecin']; ?></strong></div></article>
    <article class="card stat"><div class="stat-icon i-amber"><svg class="ic"><use href="#i-clock"/></svg></div><div><small>En attente</small><strong><?php echo (int) $pendingCount; ?></strong></div></article>
</div>

<section class="card mt">
    <form class="filters" method="get" action="index.php" role="search">
        <input type="hidden" name="page" value="users">
        <div class="field">
            <label for="f-q">Recherche</label>
            <input id="f-q" type="search" name="q" maxlength="100" placeholder="Nom ou e-mail" value="<?php echo e($filters['q']); ?>">
        </div>
        <div class="field">
            <label for="f-role">Rôle</label>
            <select id="f-role" name="role">
                <option value="">Tous</option>
                <?php foreach (['patient', 'proche', 'medecin', 'admin'] as $r): ?>
                    <option value="<?php echo e($r); ?>" <?php echo $filters['role'] === $r ? 'selected' : ''; ?>><?php echo e(roleLabel($r)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="f-status">Statut</label>
            <select id="f-status" name="status">
                <option value="">Tous</option>
                <?php foreach (['active', 'inactive', 'blocked'] as $s): ?>
                    <option value="<?php echo e($s); ?>" <?php echo $filters['status'] === $s ? 'selected' : ''; ?>><?php echo e(statusLabel($s)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filters-actions">
            <button class="btn btn-blue" type="submit">Filtrer</button>
            <a class="btn btn-ghost" href="index.php?page=users">Réinitialiser</a>
        </div>
    </form>

    <?php if ($rows === []): ?>
        <div class="empty-inline">Aucun utilisateur ne correspond à ces critères.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Nom</th><th>Rôle</th><th>Téléphone</th><th>Statut</th><th>Dernière connexion</th><th class="td-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php $name = $row['first_name'] . ' ' . $row['last_name']; $isMe = (int) $row['id'] === $user->getId(); ?>
                        <tr>
                            <td><div class="person"><span class="avatar <?php echo e(avatarClass($row['role'])); ?>"><?php echo e(initials($name)); ?></span><div><strong><?php echo e($name); ?><?php echo $isMe ? ' (vous)' : ''; ?></strong><small><?php echo e($row['email']); ?></small></div></div></td>
                            <td><span class="badge <?php echo e(roleBadge($row['role'])); ?>"><?php echo e(roleLabel($row['role'])); ?></span></td>
                            <td><?php echo e($row['phone'] !== null && $row['phone'] !== '' ? $row['phone'] : '—'); ?></td>
                            <td><span class="badge <?php echo e(statusBadge($row['status'])); ?>"><?php echo e(statusLabel($row['status'])); ?></span></td>
                            <td><?php echo e(formatDate($row['last_login_at'], true)); ?></td>
                            <td>
                                <div class="row-actions">
                                    <?php if ($row['status'] === 'inactive'): ?>
                                        <form method="post" action="index.php?page=users-status">
                                            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                            <input type="hidden" name="do" value="validate">
                                            <button class="btn btn-sm btn-ok" type="submit">Valider</button>
                                        </form>
                                    <?php elseif ($row['status'] === 'blocked'): ?>
                                        <form method="post" action="index.php?page=users-status">
                                            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                            <input type="hidden" name="do" value="unblock">
                                            <button class="btn btn-sm btn-ok" type="submit">Débloquer</button>
                                        </form>
                                    <?php elseif (!$isMe): ?>
                                        <form method="post" action="index.php?page=users-status" data-confirm="Bloquer le compte de <?php echo e($name); ?> ?">
                                            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                            <input type="hidden" name="do" value="block">
                                            <button class="btn btn-sm btn-ghost" type="submit">Bloquer</button>
                                        </form>
                                    <?php endif; ?>
                                    <a class="btn btn-sm btn-soft" href="index.php?page=users-show&amp;id=<?php echo (int) $row['id']; ?>" aria-label="Voir <?php echo e($name); ?>"><svg class="ic"><use href="#i-eye"/></svg></a>
                                    <a class="btn btn-sm btn-soft" href="index.php?page=users-edit&amp;id=<?php echo (int) $row['id']; ?>" aria-label="Modifier <?php echo e($name); ?>"><svg class="ic"><use href="#i-note"/></svg></a>
                                    <?php if (!$isMe): ?>
                                        <form method="post" action="index.php?page=users-delete" data-confirm="Supprimer définitivement <?php echo e($name); ?> ? Cette action est irréversible.">
                                            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                            <button class="btn btn-sm btn-danger" type="submit" aria-label="Supprimer <?php echo e($name); ?>"><svg class="ic"><use href="#i-trash"/></svg></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pager">
            <span><?php echo (int) $totalRows; ?> résultat<?php echo $totalRows > 1 ? 's' : ''; ?> · page <?php echo (int) $pageNumber; ?> sur <?php echo (int) $pages; ?></span>
            <?php if ($pages > 1): ?>
                <div class="pager-links">
                    <?php if ($pageNumber > 1): ?><a class="btn btn-sm btn-ghost" href="<?php echo e($pageUrl($pageNumber - 1)); ?>">Précédent</a><?php endif; ?>
                    <?php if ($pageNumber < $pages): ?><a class="btn btn-sm btn-ghost" href="<?php echo e($pageUrl($pageNumber + 1)); ?>">Suivant</a><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
