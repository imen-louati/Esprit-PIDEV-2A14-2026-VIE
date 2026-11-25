<?php
$isEdit = $mode === 'edit';
$action = $isEdit ? 'index.php?page=users-edit&id=' . $target->getId() : 'index.php?page=users-create';
?>
<div class="page-head">
    <div>
        <h1><?php echo $isEdit ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur'; ?></h1>
        <p><?php echo $isEdit ? 'Mettez à jour les informations de ' . e($target->getFullName()) . '.' : 'Créez un compte pour n\'importe quel rôle. Le mot de passe est haché avant enregistrement.'; ?></p>
    </div>
    <div class="page-actions">
        <a class="btn btn-ghost" href="index.php?page=users">Retour à la liste</a>
    </div>
</div>

<?php if (isset($errors['_form'])): ?>
    <div class="alert alert-error" role="alert"><?php echo e($errors['_form']); ?></div>
<?php endif; ?>

<form class="card form form-wide" method="post" action="<?php echo e($action); ?>" data-validate novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

    <h2>Compte</h2>
    <div class="form-row">
        <div class="field<?php echo hasError($errors, 'first_name'); ?>">
            <label for="first_name">Prénom</label>
            <input id="first_name" name="first_name" type="text" minlength="2" maxlength="100" value="<?php echo e($form['first_name']); ?>" required>
            <?php echo fieldError($errors, 'first_name'); ?>
        </div>
        <div class="field<?php echo hasError($errors, 'last_name'); ?>">
            <label for="last_name">Nom</label>
            <input id="last_name" name="last_name" type="text" minlength="2" maxlength="100" value="<?php echo e($form['last_name']); ?>" required>
            <?php echo fieldError($errors, 'last_name'); ?>
        </div>
    </div>
    <div class="form-row">
        <div class="field<?php echo hasError($errors, 'email'); ?>">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" maxlength="255" autocomplete="off" value="<?php echo e($form['email']); ?>" required>
            <?php echo fieldError($errors, 'email'); ?>
        </div>
        <div class="field<?php echo hasError($errors, 'phone'); ?>">
            <label for="phone">Téléphone <span class="optional">(facultatif)</span></label>
            <input id="phone" name="phone" type="tel" maxlength="20" data-rule="phone" value="<?php echo e($form['phone']); ?>">
            <?php echo fieldError($errors, 'phone'); ?>
        </div>
    </div>
    <div class="form-row">
        <div class="field<?php echo hasError($errors, 'role'); ?>">
            <label for="role">Rôle</label>
            <?php if ($isSelf): ?>
                <input type="hidden" name="role" value="<?php echo e($form['role']); ?>">
                <input type="text" value="<?php echo e(roleLabel($form['role'])); ?>" disabled>
                <p class="hint">Vous ne pouvez pas modifier votre propre rôle.</p>
            <?php else: ?>
                <select id="role" name="role" required>
                    <?php foreach (['patient', 'proche', 'medecin', 'admin'] as $r): ?>
                        <option value="<?php echo e($r); ?>" <?php echo $form['role'] === $r ? 'selected' : ''; ?>><?php echo e(roleLabel($r)); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php echo fieldError($errors, 'role'); ?>
        </div>
        <div class="field<?php echo hasError($errors, 'status'); ?>">
            <label for="status">Statut</label>
            <?php if ($isSelf): ?>
                <input type="hidden" name="status" value="<?php echo e($form['status']); ?>">
                <input type="text" value="<?php echo e(statusLabel($form['status'])); ?>" disabled>
            <?php else: ?>
                <select id="status" name="status" required>
                    <?php foreach (['active', 'inactive', 'blocked'] as $s): ?>
                        <option value="<?php echo e($s); ?>" <?php echo $form['status'] === $s ? 'selected' : ''; ?>><?php echo e(statusLabel($s)); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php echo fieldError($errors, 'status'); ?>
        </div>
    </div>
    <div class="form-row">
        <div class="field<?php echo hasError($errors, 'password'); ?>">
            <label for="password">Mot de passe <?php echo $isEdit ? '<span class="optional">(laisser vide pour ne pas changer)</span>' : ''; ?></label>
            <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" data-rule="password" <?php echo $isEdit ? '' : 'required'; ?>>
            <?php echo fieldError($errors, 'password'); ?>
        </div>
        <div class="field<?php echo hasError($errors, 'password_confirm'); ?>">
            <label for="password_confirm">Confirmation</label>
            <input id="password_confirm" name="password_confirm" type="password" maxlength="72" autocomplete="new-password" data-match="#password" <?php echo $isEdit ? '' : 'required'; ?>>
            <?php echo fieldError($errors, 'password_confirm'); ?>
        </div>
    </div>

    <div class="form-section" data-visible-if="role=patient" <?php echo $form['role'] === 'patient' ? '' : 'hidden'; ?>>
        <h2>Informations patient</h2>
        <div class="form-row">
            <div class="field<?php echo hasError($errors, 'birth_date'); ?>">
                <label for="birth_date">Date de naissance</label>
                <input id="birth_date" name="birth_date" type="date" min="1900-01-01" max="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e($form['birth_date']); ?>" required>
                <?php echo fieldError($errors, 'birth_date'); ?>
            </div>
            <div class="field<?php echo hasError($errors, 'cancer_type'); ?>">
                <label for="cancer_type">Type de cancer</label>
                <select id="cancer_type" name="cancer_type" required>
                    <option value="">Choisir</option>
                    <?php foreach ($cancerTypes as $item): ?>
                        <option value="<?php echo e($item); ?>" <?php echo $form['cancer_type'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo fieldError($errors, 'cancer_type'); ?>
            </div>
        </div>
        <div class="form-row">
            <div class="field<?php echo hasError($errors, 'stage'); ?>">
                <label for="stage">Stade</label>
                <select id="stage" name="stage" required>
                    <option value="">Choisir</option>
                    <?php foreach ($stages as $item): ?>
                        <option value="<?php echo e($item); ?>" <?php echo $form['stage'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo fieldError($errors, 'stage'); ?>
            </div>
            <?php if ($isEdit && $target->getShareCode() !== ''): ?>
                <div class="field">
                    <label>Code de partage</label>
                    <input type="text" value="<?php echo e($target->getShareCode()); ?>" disabled>
                </div>
            <?php else: ?>
                <div class="field"><p class="hint" style="margin-top:30px">Le code de partage est généré automatiquement.</p></div>
            <?php endif; ?>
        </div>
        <div class="field<?php echo hasError($errors, 'progress'); ?>">
            <label for="progress">État d'avancement <span class="optional">(facultatif)</span></label>
            <textarea id="progress" name="progress" maxlength="1000" placeholder="Ex. chimiothérapie en cours, 3 cycles sur 6"><?php echo e($form['progress']); ?></textarea>
            <?php echo fieldError($errors, 'progress'); ?>
        </div>
    </div>

    <div class="form-section" data-visible-if="role=medecin" <?php echo $form['role'] === 'medecin' ? '' : 'hidden'; ?>>
        <h2>Informations médecin</h2>
        <div class="form-row">
            <div class="field<?php echo hasError($errors, 'speciality'); ?>">
                <label for="speciality">Spécialité</label>
                <select id="speciality" name="speciality" required>
                    <option value="">Choisir</option>
                    <?php foreach ($specialities as $item): ?>
                        <option value="<?php echo e($item); ?>" <?php echo $form['speciality'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo fieldError($errors, 'speciality'); ?>
            </div>
            <div class="field<?php echo hasError($errors, 'license_number'); ?>">
                <label for="license_number">N° de licence</label>
                <input id="license_number" name="license_number" type="text" maxlength="30" pattern="[A-Za-z0-9\-\/]{4,30}" data-error-pattern="4 à 30 caractères (lettres, chiffres, - et /)." value="<?php echo e($form['license_number']); ?>" required>
                <?php echo fieldError($errors, 'license_number'); ?>
            </div>
        </div>
        <div class="field<?php echo hasError($errors, 'city'); ?>">
            <label for="city">Ville</label>
            <input id="city" name="city" type="text" maxlength="100" value="<?php echo e($form['city']); ?>" required>
            <?php echo fieldError($errors, 'city'); ?>
        </div>
    </div>

    <div class="form-section" data-visible-if="role=proche" <?php echo $form['role'] === 'proche' ? '' : 'hidden'; ?>>
        <h2>Lien avec un patient</h2>
        <div class="form-row">
            <div class="field<?php echo hasError($errors, 'patient_code'); ?>">
                <label for="patient_code">Code du patient</label>
                <input id="patient_code" name="patient_code" type="text" maxlength="10" placeholder="CC-7F3K-92" pattern="CC-[A-Za-z0-9]{4}-[0-9]{2}" data-error-pattern="Format attendu : CC-XXXX-00." value="<?php echo e($form['patient_code']); ?>" required>
                <?php echo fieldError($errors, 'patient_code'); ?>
            </div>
            <div class="field<?php echo hasError($errors, 'relationship'); ?>">
                <label for="relationship">Lien</label>
                <select id="relationship" name="relationship" required>
                    <option value="">Choisir</option>
                    <?php foreach ($relationships as $item): ?>
                        <option value="<?php echo e($item); ?>" <?php echo $form['relationship'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo fieldError($errors, 'relationship'); ?>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a class="btn btn-ghost" href="index.php?page=users">Annuler</a>
        <button class="btn btn-primary" type="submit"><?php echo $isEdit ? 'Enregistrer les modifications' : 'Créer l\'utilisateur'; ?></button>
    </div>
</form>
