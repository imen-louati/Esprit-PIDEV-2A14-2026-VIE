<?php
$labels = ['patient' => 'Patients', 'proche' => 'Proches', 'medecin' => 'Médecins', 'admin' => 'Équipe'];
$colors = ['patient' => 'var(--fuchsia)', 'proche' => 'var(--blue)', 'medecin' => 'var(--violet)', 'admin' => 'var(--marine)'];
$peakKey = array_key_last($months);
?>
<div class="page-head">
    <div>
        <h1>Tableau de bord</h1>
        <p>Vue d'ensemble de la plateforme VIE — <?php echo e(formatDate(date('Y-m-d'))); ?></p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="index.php?page=users-create"><svg class="ic"><use href="#i-plus"/></svg>Nouveau compte</a>
    </div>
</div>

<div class="grid cols-4">
    <article class="card stat">
        <div class="stat-icon i-pink"><svg class="ic"><use href="#i-users"/></svg></div>
        <div><small>Comptes</small><strong><?php echo (int) $total; ?></strong><span class="delta flat"><?php echo (int) $counts['patient']; ?> patients · <?php echo (int) $counts['proche']; ?> proches</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-violet"><svg class="ic"><use href="#i-stetho"/></svg></div>
        <div><small>Médecins</small><strong><?php echo (int) $counts['medecin']; ?></strong><span class="delta <?php echo $pendingDoctors > 0 ? 'down' : 'flat'; ?>"><?php echo (int) $pendingDoctors; ?> à valider</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-blue"><svg class="ic"><use href="#i-heart"/></svg></div>
        <div><small>Patients</small><strong><?php echo (int) $counts['patient']; ?></strong><span class="delta flat">Comptes malades</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-amber"><svg class="ic"><use href="#i-lock"/></svg></div>
        <div><small>Comptes bloqués</small><strong><?php echo (int) $blocked; ?></strong><span class="delta flat">Accès suspendu</span></div>
    </article>
</div>

<div class="grid main-side mt">
    <div class="stack">
        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Derniers comptes créés</h2>
                    <p>Validez, bloquez ou ouvrez la fiche d'un utilisateur.</p>
                </div>
                <a class="link" href="index.php?page=users">Tous les utilisateurs</a>
            </div>
            <?php if ($latestUsers === []): ?>
                <div class="empty-inline">Aucun compte pour le moment.</div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Nom</th><th>Rôle</th><th>Statut</th><th>Inscrit le</th><th class="td-end">Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestUsers as $row): ?>
                                <?php $name = $row['first_name'] . ' ' . $row['last_name']; ?>
                                <tr>
                                    <td><div class="person"><span class="avatar <?php echo e(avatarClass($row['role'])); ?>"><?php echo e(initials($name)); ?></span><div><strong><?php echo e($name); ?></strong><small><?php echo e($row['email']); ?></small></div></div></td>
                                    <td><span class="badge <?php echo e(roleBadge($row['role'])); ?>"><?php echo e(roleLabel($row['role'])); ?></span></td>
                                    <td><span class="badge <?php echo e(statusBadge($row['status'])); ?>"><?php echo e(statusLabel($row['status'])); ?></span></td>
                                    <td><?php echo e(formatDate($row['created_at'])); ?></td>
                                    <td>
                                        <div class="row-actions">
                                            <?php if ($row['status'] === 'inactive'): ?>
                                                <form method="post" action="index.php?page=users-status">
                                                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                                    <input type="hidden" name="do" value="validate">
                                                    <input type="hidden" name="return" value="dashboard">
                                                    <button class="btn btn-sm btn-ok" type="submit">Valider</button>
                                                </form>
                                            <?php endif; ?>
                                            <a class="btn btn-sm btn-soft" href="index.php?page=users-show&amp;id=<?php echo (int) $row['id']; ?>">Voir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Nouveaux comptes</h2>
                    <p>Inscriptions par mois sur les 6 derniers mois</p>
                </div>
                <div class="legend"><span><i style="background:var(--blue)"></i>Inscriptions</span><span><i style="background:var(--fuchsia)"></i>Mois en cours</span></div>
            </div>
            <div class="bars" role="img" aria-label="Histogramme des inscriptions mensuelles">
                <?php foreach ($months as $key => $month): ?>
                    <div class="bar-col <?php echo $key === $peakKey ? 'is-peak' : ''; ?>">
                        <b class="bar-value"><?php echo (int) $month['count']; ?></b>
                        <i style="height:<?php echo (int) $month['height']; ?>%"></i>
                        <span><?php echo e($month['label']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="card-head"><h2>Répartition des comptes</h2></div>
            <div class="donut-wrap">
                <div class="donut" style="background:conic-gradient(<?php echo e($donut); ?>)" role="img" aria-label="Répartition des comptes par rôle">
                    <div class="donut-center"><strong><?php echo (int) $total; ?></strong><small>comptes</small></div>
                </div>
                <ul class="donut-legend">
                    <?php foreach ($labels as $key => $label): ?>
                        <li><i style="background:<?php echo e($colors[$key]); ?>"></i><?php echo e($label); ?><b><?php echo (int) $counts[$key]; ?></b></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <h2>Médecins à valider</h2>
                <span class="badge <?php echo $pendingDoctors > 0 ? 'b-amber' : 'b-gray'; ?>"><?php echo (int) $pendingDoctors; ?></span>
            </div>
            <?php if ($pending === []): ?>
                <div class="empty-inline">Aucun médecin en attente de validation.</div>
            <?php else: ?>
                <div class="mini-list">
                    <?php foreach ($pending as $doctor): ?>
                        <?php $name = $doctor['first_name'] . ' ' . $doctor['last_name']; ?>
                        <div class="mini">
                            <span class="avatar av-violet"><?php echo e(initials($name)); ?></span>
                            <div class="grow"><strong><?php echo e($name); ?></strong><small><?php echo e($doctor['speciality']); ?> · Licence <?php echo e($doctor['license_number']); ?> · <?php echo e($doctor['city']); ?></small></div>
                            <form method="post" action="index.php?page=users-status">
                                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                <input type="hidden" name="id" value="<?php echo (int) $doctor['id']; ?>">
                                <input type="hidden" name="do" value="validate">
                                <input type="hidden" name="return" value="dashboard">
                                <button class="btn btn-sm btn-ok" type="submit">Valider</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="card">
            <div class="card-head"><h2>Dernières connexions</h2></div>
            <?php if ($recentLogins === []): ?>
                <div class="empty-inline">Aucune connexion enregistrée.</div>
            <?php else: ?>
                <ul class="timeline">
                    <?php foreach ($recentLogins as $login): ?>
                        <?php $name = $login['first_name'] . ' ' . $login['last_name']; ?>
                        <li class="<?php echo $login['role'] === 'medecin' ? 'violet' : ($login['role'] === 'proche' ? 'blue' : ($login['role'] === 'admin' ? 'green' : '')); ?>">
                            <time><?php echo e(formatDate($login['last_login_at'], true)); ?></time>
                            <strong><?php echo e($name); ?></strong>
                            <p><?php echo e(roleLabel($login['role'])); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
</div>
