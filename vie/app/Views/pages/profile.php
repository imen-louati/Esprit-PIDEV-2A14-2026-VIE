<?php
$role = $user->getRole();
$fullName = $user->getFullName();
?>
<div class="page-head">
    <div>
        <h1>Mon profil</h1>
        <p>Gérez vos informations personnelles, votre mot de passe et votre compte.</p>
    </div>
</div>

<div class="grid main-side">
    <div class="stack">
        <section class="card" id="info">
            <div class="card-head"><h2>Informations personnelles</h2></div>
            <?php if (isset($errors['_info'])): ?>
                <div class="alert alert-error" role="alert"><?php echo e($errors['_info']); ?></div>
            <?php endif; ?>
            <form class="form" method="post" action="index.php?page=profile-update" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <div class="form-row">
                    <div class="field<?php echo hasError($errors, 'first_name'); ?>">
                        <label for="first_name">Prénom</label>
                        <input id="first_name" name="first_name" type="text" minlength="2" maxlength="100" value="<?php echo e($old['first_name']); ?>" required>
                        <?php echo fieldError($errors, 'first_name'); ?>
                    </div>
                    <div class="field<?php echo hasError($errors, 'last_name'); ?>">
                        <label for="last_name">Nom</label>
                        <input id="last_name" name="last_name" type="text" minlength="2" maxlength="100" value="<?php echo e($old['last_name']); ?>" required>
                        <?php echo fieldError($errors, 'last_name'); ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field<?php echo hasError($errors, 'email'); ?>">
                        <label for="email">Adresse e-mail</label>
                        <input id="email" name="email" type="email" maxlength="255" value="<?php echo e($old['email']); ?>" required>
                        <?php echo fieldError($errors, 'email'); ?>
                    </div>
                    <div class="field<?php echo hasError($errors, 'phone'); ?>">
                        <label for="phone">Téléphone <span class="optional">(facultatif)</span></label>
                        <input id="phone" name="phone" type="tel" maxlength="20" data-rule="phone" value="<?php echo e($old['phone']); ?>">
                        <?php echo fieldError($errors, 'phone'); ?>
                    </div>
                </div>

                <?php if ($role === 'patient'): ?>
                    <div class="form-row">
                        <div class="field<?php echo hasError($errors, 'birth_date'); ?>">
                            <label for="birth_date">Date de naissance</label>
                            <input id="birth_date" name="birth_date" type="date" min="1900-01-01" max="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e($old['birth_date']); ?>" required>
                            <?php echo fieldError($errors, 'birth_date'); ?>
                        </div>
                        <div class="field<?php echo hasError($errors, 'cancer_type'); ?>">
                            <label for="cancer_type">Type de cancer</label>
                            <select id="cancer_type" name="cancer_type" required>
                                <option value="">Choisir</option>
                                <?php foreach ($cancerTypes as $item): ?>
                                    <option value="<?php echo e($item); ?>" <?php echo $old['cancer_type'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo fieldError($errors, 'cancer_type'); ?>
                        </div>
                    </div>
                    <div class="field<?php echo hasError($errors, 'stage'); ?>">
                        <label for="stage">Stade</label>
                        <select id="stage" name="stage" required>
                            <option value="">Choisir</option>
                            <?php foreach ($stages as $item): ?>
                                <option value="<?php echo e($item); ?>" <?php echo $old['stage'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo fieldError($errors, 'stage'); ?>
                    </div>
                    <div class="field<?php echo hasError($errors, 'progress'); ?>">
                        <label for="progress">État d'avancement <span class="optional">(facultatif)</span></label>
                        <textarea id="progress" name="progress" maxlength="1000"><?php echo e($old['progress']); ?></textarea>
                        <?php echo fieldError($errors, 'progress'); ?>
                    </div>
                <?php elseif ($role === 'medecin'): ?>
                    <div class="form-row">
                        <div class="field<?php echo hasError($errors, 'speciality'); ?>">
                            <label for="speciality">Spécialité</label>
                            <select id="speciality" name="speciality" required>
                                <option value="">Choisir</option>
                                <?php foreach ($specialities as $item): ?>
                                    <option value="<?php echo e($item); ?>" <?php echo $old['speciality'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo fieldError($errors, 'speciality'); ?>
                        </div>
                        <div class="field<?php echo hasError($errors, 'city'); ?>">
                            <label for="city">Ville</label>
                            <input id="city" name="city" type="text" maxlength="100" value="<?php echo e($old['city']); ?>" required>
                            <?php echo fieldError($errors, 'city'); ?>
                        </div>
                    </div>
                    <div class="field">
                        <label>N° de licence</label>
                        <input type="text" value="<?php echo e($user->getLicenseNumber()); ?>" disabled>
                        <p class="hint">Le n° de licence ne peut être modifié que par l'administration.</p>
                    </div>
                <?php elseif ($role === 'proche'): ?>
                    <div class="form-row">
                        <div class="field<?php echo hasError($errors, 'relationship'); ?>">
                            <label for="relationship">Lien avec le patient</label>
                            <select id="relationship" name="relationship" required>
                                <option value="">Choisir</option>
                                <?php foreach ($relationships as $item): ?>
                                    <option value="<?php echo e($item); ?>" <?php echo $old['relationship'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo fieldError($errors, 'relationship'); ?>
                        </div>
                        <div class="field">
                            <label>Patient lié</label>
                            <input type="text" value="<?php echo $patient !== null ? e($patient->getFullName()) : 'Aucun patient lié'; ?>" disabled>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </form>
        </section>

        <section class="card" id="password">
            <div class="card-head"><h2>Changer le mot de passe</h2></div>
            <?php if (isset($errors['_password'])): ?>
                <div class="alert alert-error" role="alert"><?php echo e($errors['_password']); ?></div>
            <?php endif; ?>
            <form class="form" method="post" action="index.php?page=profile-password" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <div class="field<?php echo hasError($errors, 'current_password'); ?>">
                    <label for="current_password">Mot de passe actuel</label>
                    <input id="current_password" name="current_password" type="password" maxlength="72" autocomplete="current-password" required>
                    <?php echo fieldError($errors, 'current_password'); ?>
                </div>
                <div class="form-row">
                    <div class="field<?php echo hasError($errors, 'new_password'); ?>">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input id="new_password" name="new_password" type="password" minlength="8" maxlength="72" autocomplete="new-password" data-rule="password" required>
                        <?php echo fieldError($errors, 'new_password'); ?>
                    </div>
                    <div class="field<?php echo hasError($errors, 'new_password_confirm'); ?>">
                        <label for="new_password_confirm">Confirmation</label>
                        <input id="new_password_confirm" name="new_password_confirm" type="password" maxlength="72" autocomplete="new-password" data-match="#new_password" required>
                        <?php echo fieldError($errors, 'new_password_confirm'); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-blue" type="submit"><svg class="ic"><use href="#i-key"/></svg>Modifier le mot de passe</button>
                </div>
            </form>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="profile-head">
                <span class="avatar avatar-lg <?php echo e(avatarClass($role)); ?>"><?php echo e(initials($fullName)); ?></span>
                <div>
                    <h2><?php echo e($fullName); ?></h2>
                    <div class="tags">
                        <span class="badge <?php echo e(roleBadge($role)); ?>"><?php echo e(roleLabel($role)); ?></span>
                        <span class="badge <?php echo e(statusBadge($user->getStatus())); ?>"><?php echo e(statusLabel($user->getStatus())); ?></span>
                    </div>
                </div>
            </div>
            <dl class="kv kv-wide">
                <div><dt>Membre depuis</dt><dd><?php echo e(formatDate($user->getCreatedAt())); ?></dd></div>
                <div><dt>Dernière connexion</dt><dd><?php echo e(formatDate($user->getLastLoginAt(), true)); ?></dd></div>
            </dl>
            <?php if ($role === 'patient' && $user->getShareCode() !== ''): ?>
                <div class="code-box mt">
                    <div><small>Mon code de partage</small><strong><?php echo e($user->getShareCode()); ?></strong></div>
                    <button class="btn btn-sm btn-primary" type="button" data-action="copy" data-value="<?php echo e($user->getShareCode()); ?>"><svg class="ic"><use href="#i-copy"/></svg>Copier</button>
                </div>
            <?php endif; ?>
        </section>

        <section class="card card-danger" id="delete">
            <div class="card-head"><h2>Supprimer mon compte</h2></div>
            <p class="muted">Cette action est définitive : vos données seront supprimées.</p>
            <?php if (isset($errors['_delete'])): ?>
                <div class="alert alert-error" role="alert"><?php echo e($errors['_delete']); ?></div>
            <?php endif; ?>
            <form class="form mt" method="post" action="index.php?page=profile-delete" data-validate data-confirm="Supprimer définitivement votre compte ?" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <div class="field<?php echo hasError($errors, 'delete_password'); ?>">
                    <label for="delete_password">Confirmez avec votre mot de passe</label>
                    <input id="delete_password" name="delete_password" type="password" maxlength="72" autocomplete="current-password" required>
                    <?php echo fieldError($errors, 'delete_password'); ?>
                </div>
                <div class="field<?php echo hasError($errors, 'delete_confirm'); ?>">
                    <label class="check-row"><input type="checkbox" name="delete_confirm" value="1" required> <span>Je comprends que la suppression est irréversible.</span></label>
                    <?php echo fieldError($errors, 'delete_confirm'); ?>
                </div>
                <button class="btn btn-danger btn-block" type="submit"><svg class="ic"><use href="#i-trash"/></svg>Supprimer mon compte</button>
            </form>
        </section>
    </div>
</div>
