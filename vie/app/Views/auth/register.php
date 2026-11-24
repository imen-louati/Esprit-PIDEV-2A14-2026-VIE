<?php
$errors = $errors ?? [];
$isMedecin = !$firstAccount && $old['account'] === 'medecin';
$isPatient = !$firstAccount && $old['account'] === 'personne' && $old['person_type'] === 'patient';
$isProche = !$firstAccount && $old['account'] === 'personne' && $old['person_type'] === 'proche';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once __DIR__ . '/../../../includes/head.php'; ?>
</head>
<body>
    <div class="page-bg" aria-hidden="true"></div>
    <main class="auth">
        <section class="auth-art">
            <a href="home.php" aria-label="Retour à l'accueil"><img src="assets/img/logo-vie-clair.png" alt="VIE"></a>
            <blockquote>
                <p>Rejoignez VIE : un seul endroit pour informer, accompagner et orienter.</p>
                <footer>Patients, proches et médecins, autour du même parcours.</footer>
            </blockquote>
        </section>

        <section class="auth-panel auth-panel-scroll">
            <a class="back-home" href="home.php">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                Retour au site
            </a>
            <div class="auth-card glass auth-card-wide">
                <h1>Créer un compte</h1>
                <p class="sub">Choisissez votre profil, puis renseignez vos informations.</p>

                <?php if ($firstAccount): ?>
                    <div class="alert alert-info" role="status">Aucun compte n'existe encore : le premier compte créé devient automatiquement <strong>administrateur</strong>.</div>
                <?php endif; ?>
                <?php foreach ($flashes as $flash): ?>
                    <div class="alert alert-<?php echo e($flash['type']); ?>" role="alert"><?php echo e($flash['message']); ?></div>
                <?php endforeach; ?>
                <?php if (isset($errors['_form'])): ?>
                    <div class="alert alert-error" role="alert"><?php echo e($errors['_form']); ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=register" data-validate novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

                    <?php if (!$firstAccount): ?>
                        <fieldset class="field">
                            <legend>Je suis</legend>
                            <div class="segmented">
                                <label><input type="radio" name="account" value="personne" <?php echo $old['account'] === 'personne' ? 'checked' : ''; ?>><span>Une personne</span></label>
                                <label><input type="radio" name="account" value="medecin" <?php echo $old['account'] === 'medecin' ? 'checked' : ''; ?>><span>Un médecin</span></label>
                            </div>
                            <?php echo fieldError($errors, 'account'); ?>
                        </fieldset>

                        <fieldset class="field" data-visible-if="account=personne" <?php echo $old['account'] === 'personne' ? '' : 'hidden'; ?>>
                            <legend>Vous êtes</legend>
                            <div class="segmented">
                                <label><input type="radio" name="person_type" value="patient" <?php echo $old['person_type'] === 'patient' ? 'checked' : ''; ?>><span>Malade</span></label>
                                <label><input type="radio" name="person_type" value="proche" <?php echo $old['person_type'] === 'proche' ? 'checked' : ''; ?>><span>Proche d'un malade</span></label>
                            </div>
                            <?php echo fieldError($errors, 'person_type'); ?>
                        </fieldset>
                    <?php endif; ?>

                    <div class="field-row">
                        <div class="field<?php echo hasError($errors, 'first_name'); ?>">
                            <label for="first_name">Prénom</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Prénom" autocomplete="given-name" minlength="2" maxlength="100" value="<?php echo e($old['first_name']); ?>" required>
                            <?php echo fieldError($errors, 'first_name'); ?>
                        </div>
                        <div class="field<?php echo hasError($errors, 'last_name'); ?>">
                            <label for="last_name">Nom</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Nom" autocomplete="family-name" minlength="2" maxlength="100" value="<?php echo e($old['last_name']); ?>" required>
                            <?php echo fieldError($errors, 'last_name'); ?>
                        </div>
                    </div>
                    <div class="field<?php echo hasError($errors, 'email'); ?>">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="nom@exemple.com" autocomplete="email" maxlength="255" value="<?php echo e($old['email']); ?>" required>
                        <?php echo fieldError($errors, 'email'); ?>
                    </div>
                    <div class="field<?php echo hasError($errors, 'phone'); ?>">
                        <label for="phone">Téléphone <span class="optional">(facultatif)</span></label>
                        <input type="tel" id="phone" name="phone" placeholder="+216 20 000 000" autocomplete="tel" maxlength="20" data-rule="phone" value="<?php echo e($old['phone']); ?>">
                        <?php echo fieldError($errors, 'phone'); ?>
                    </div>

                    <div data-visible-if="account=personne;person_type=patient" <?php echo $isPatient ? '' : 'hidden'; ?>>
                        <div class="field-row">
                            <div class="field<?php echo hasError($errors, 'birth_date'); ?>">
                                <label for="birth_date">Date de naissance</label>
                                <input type="date" id="birth_date" name="birth_date" min="1900-01-01" max="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e($old['birth_date']); ?>" required>
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
                            <textarea id="progress" name="progress" maxlength="1000" placeholder="Ex. traitement en cours, prochain contrôle en janvier"><?php echo e($old['progress']); ?></textarea>
                            <?php echo fieldError($errors, 'progress'); ?>
                            <p class="hint">Un code de partage personnel vous sera attribué pour inviter vos proches.</p>
                        </div>
                    </div>

                    <div data-visible-if="account=medecin" <?php echo $isMedecin ? '' : 'hidden'; ?>>
                        <div class="field<?php echo hasError($errors, 'speciality'); ?>">
                            <label for="speciality">Spécialité</label>
                            <select id="speciality" name="speciality" required>
                                <option value="">Choisir une spécialité</option>
                                <?php foreach ($specialities as $item): ?>
                                    <option value="<?php echo e($item); ?>" <?php echo $old['speciality'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo fieldError($errors, 'speciality'); ?>
                        </div>
                        <div class="field-row">
                            <div class="field<?php echo hasError($errors, 'license_number'); ?>">
                                <label for="license_number">N° de licence</label>
                                <input type="text" id="license_number" name="license_number" placeholder="Ex. 12345" maxlength="30" pattern="[A-Za-z0-9\-\/]{4,30}" data-error-pattern="4 à 30 caractères (lettres, chiffres, - et /)." value="<?php echo e($old['license_number']); ?>" required>
                                <?php echo fieldError($errors, 'license_number'); ?>
                            </div>
                            <div class="field<?php echo hasError($errors, 'city'); ?>">
                                <label for="city">Ville</label>
                                <input type="text" id="city" name="city" placeholder="Tunis" maxlength="100" value="<?php echo e($old['city']); ?>" required>
                                <?php echo fieldError($errors, 'city'); ?>
                            </div>
                        </div>
                        <p class="hint">Votre profil sera validé par l'administration avant votre première connexion.</p>
                    </div>

                    <div data-visible-if="account=personne;person_type=proche" <?php echo $isProche ? '' : 'hidden'; ?>>
                        <div class="field<?php echo hasError($errors, 'patient_code'); ?>">
                            <label for="patient_code">Code du malade</label>
                            <input type="text" id="patient_code" name="patient_code" placeholder="CC-7F3K-92" maxlength="10" autocapitalize="characters" pattern="CC-[A-Za-z0-9]{4}-[0-9]{2}" data-error-pattern="Format attendu : CC-XXXX-00." value="<?php echo e($old['patient_code']); ?>" required>
                            <?php echo fieldError($errors, 'patient_code'); ?>
                            <p class="hint">Demandez ce code au malade : il le trouve dans son espace.</p>
                        </div>
                        <div class="field<?php echo hasError($errors, 'relationship'); ?>">
                            <label for="relationship">Lien avec le malade</label>
                            <select id="relationship" name="relationship" required>
                                <option value="">Choisir</option>
                                <?php foreach ($relationships as $item): ?>
                                    <option value="<?php echo e($item); ?>" <?php echo $old['relationship'] === $item ? 'selected' : ''; ?>><?php echo e($item); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo fieldError($errors, 'relationship'); ?>
                        </div>
                    </div>

                    <div class="field<?php echo hasError($errors, 'password'); ?>">
                        <label for="password">Mot de passe</label>
                        <div class="pw-wrap">
                            <input type="password" id="password" name="password" placeholder="8 caractères minimum" autocomplete="new-password" minlength="8" maxlength="72" data-rule="password" required>
                            <button class="pw-toggle" type="button" data-toggle-password="#password" aria-label="Afficher le mot de passe">Afficher</button>
                        </div>
                        <div class="strength" data-strength-for="#password" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                        <?php echo fieldError($errors, 'password'); ?>
                        <p class="hint">Au moins une majuscule, une minuscule et un chiffre.</p>
                    </div>
                    <div class="field<?php echo hasError($errors, 'password_confirm'); ?>">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Retapez le mot de passe" autocomplete="new-password" maxlength="72" data-match="#password" required>
                        <?php echo fieldError($errors, 'password_confirm'); ?>
                    </div>

                    <div class="field<?php echo hasError($errors, 'accept'); ?>">
                        <label class="check-row"><input type="checkbox" name="accept" value="1" required> <span>J'accepte que mes données de santé soient traitées dans le cadre de la plateforme VIE.</span></label>
                        <?php echo fieldError($errors, 'accept'); ?>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">Créer mon compte</button>
                </form>

                <p class="auth-foot">Déjà inscrit ? <a href="index.php?page=login">Se connecter</a></p>
            </div>
        </section>
    </main>
    <script src="assets/js/forms.js"></script>
</body>
</html>
