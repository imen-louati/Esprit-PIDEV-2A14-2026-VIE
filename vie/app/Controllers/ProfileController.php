<?php

namespace App\Controllers;

use App\Core\Flash;
use App\Core\Security;
use App\Core\Validator;

class ProfileController extends Controller
{
    protected string $page = 'profile';
    protected string $title = 'Mon profil | VIE';
    protected array $allowedRoles = ['patient', 'proche', 'medecin', 'admin'];

    public function index(): void
    {
        $this->showProfile();
    }

    private function currentValues(): array
    {
        return [
            'first_name' => $this->user->getFirstName(),
            'last_name' => $this->user->getLastName(),
            'email' => $this->user->getEmail(),
            'phone' => $this->user->getPhone(),
            'birth_date' => $this->user->getBirthDate(),
            'cancer_type' => $this->user->getCancerType(),
            'stage' => $this->user->getStage(),
            'progress' => $this->user->getProgress(),
            'speciality' => $this->user->getSpeciality(),
            'city' => $this->user->getCity(),
            'relationship' => $this->user->getRelationship(),
        ];
    }

    private function showProfile(array $errors = [], ?array $old = null, string $section = ''): void
    {
        $patient = null;
        if ($this->user->getRole() === 'proche' && $this->user->getPatientId() > 0) {
            $patient = $this->findUserById($this->user->getPatientId());
        }

        $this->render('profile', [
            'errors' => $errors,
            'old' => $old ?? $this->currentValues(),
            'section' => $section,
            'patient' => $patient,
            'specialities' => Validator::SPECIALITIES,
            'relationships' => Validator::RELATIONSHIPS,
            'cancerTypes' => Validator::CANCER_TYPES,
            'stages' => Validator::STAGES,
        ]);
    }

    public function update(): void
    {
        $this->requirePost();

        $old = $this->currentValues();
        foreach (array_keys($old) as $key) {
            $old[$key] = $this->post($key);
        }
        $old['email'] = strtolower($old['email']);

        if (!$this->csrfValid()) {
            $this->showProfile(['_info' => 'Jeton de sécurité invalide. Rechargez la page.'], $old, 'info');
            return;
        }

        $errors = [];
        if (($m = Validator::name($old['first_name'], 'Prénom')) !== null) {
            $errors['first_name'] = $m;
        }
        if (($m = Validator::name($old['last_name'], 'Nom')) !== null) {
            $errors['last_name'] = $m;
        }
        if (($m = Validator::email($old['email'])) !== null) {
            $errors['email'] = $m;
        } elseif ($this->emailExists($old['email'], $this->user->getId())) {
            $errors['email'] = 'Cette adresse e-mail est déjà utilisée.';
        }
        if (($m = Validator::phone($old['phone'])) !== null) {
            $errors['phone'] = $m;
        }

        $role = $this->user->getRole();
        $columns = [
            'first_name' => $old['first_name'],
            'last_name' => $old['last_name'],
            'email' => $old['email'],
            'phone' => $this->nullIfEmpty($old['phone']),
        ];

        if ($role === 'patient') {
            if (($m = Validator::birthDate($old['birth_date'])) !== null) {
                $errors['birth_date'] = $m;
            }
            if (($m = Validator::inList($old['cancer_type'], Validator::CANCER_TYPES, 'Choisissez un type de cancer.')) !== null) {
                $errors['cancer_type'] = $m;
            }
            if (($m = Validator::inList($old['stage'], Validator::STAGES, 'Choisissez un stade.')) !== null) {
                $errors['stage'] = $m;
            }
            if (($m = Validator::progress($old['progress'])) !== null) {
                $errors['progress'] = $m;
            }
            $columns['birth_date'] = $this->nullIfEmpty($old['birth_date']);
            $columns['cancer_type'] = $this->nullIfEmpty($old['cancer_type']);
            $columns['stage'] = $this->nullIfEmpty($old['stage']);
            $columns['progress'] = $this->nullIfEmpty($old['progress']);
        } elseif ($role === 'medecin') {
            if (($m = Validator::inList($old['speciality'], Validator::SPECIALITIES, 'Choisissez une spécialité dans la liste.')) !== null) {
                $errors['speciality'] = $m;
            }
            if (($m = Validator::city($old['city'])) !== null) {
                $errors['city'] = $m;
            }
            $columns['speciality'] = $this->nullIfEmpty($old['speciality']);
            $columns['city'] = $this->nullIfEmpty($old['city']);
        } elseif ($role === 'proche') {
            if (($m = Validator::inList($old['relationship'], Validator::RELATIONSHIPS, 'Choisissez votre lien avec le patient.')) !== null) {
                $errors['relationship'] = $m;
            }
            $columns['relationship'] = $this->nullIfEmpty($old['relationship']);
        }

        if ($errors !== []) {
            $this->showProfile($errors, $old, 'info');
            return;
        }

        $assignments = [];
        foreach (array_keys($columns) as $name) {
            $assignments[] = $name . ' = :' . $name;
        }
        $columns['id'] = $this->user->getId();
        $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $assignments) . ' WHERE id = :id');
        $stmt->execute($columns);

        Flash::set('success', 'Votre profil a été mis à jour.');
        $this->redirect('profile');
    }

    public function changePassword(): void
    {
        $this->requirePost();

        if (!$this->csrfValid()) {
            $this->showProfile(['_password' => 'Jeton de sécurité invalide. Rechargez la page.'], null, 'password');
            return;
        }

        $current = $this->rawPost('current_password');
        $new = $this->rawPost('new_password');
        $confirm = $this->rawPost('new_password_confirm');
        $errors = [];

        if ($current === '' || !password_verify($current, $this->user->getPassword())) {
            $errors['current_password'] = 'Le mot de passe actuel est incorrect.';
        }
        if (($m = Validator::password($new)) !== null) {
            $errors['new_password'] = $m;
        } elseif ($new !== $confirm) {
            $errors['new_password_confirm'] = 'Les mots de passe ne correspondent pas.';
        } elseif ($current !== '' && hash_equals($current, $new)) {
            $errors['new_password'] = 'Le nouveau mot de passe doit être différent de l\'actuel.';
        }

        if ($errors !== []) {
            $this->showProfile($errors, null, 'password');
            return;
        }

        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        $stmt->execute(['password' => password_hash($new, PASSWORD_DEFAULT), 'id' => $this->user->getId()]);
        session_regenerate_id(true);
        Flash::set('success', 'Votre mot de passe a été modifié.');
        $this->redirect('profile');
    }

    public function deleteAccount(): void
    {
        $this->requirePost();

        if (!$this->csrfValid()) {
            $this->showProfile(['_delete' => 'Jeton de sécurité invalide. Rechargez la page.'], null, 'delete');
            return;
        }

        $errors = [];
        if (!password_verify($this->rawPost('delete_password'), $this->user->getPassword())) {
            $errors['delete_password'] = 'Mot de passe incorrect.';
        }
        if ($this->post('delete_confirm') !== '1') {
            $errors['delete_confirm'] = 'Cochez la case pour confirmer la suppression.';
        }
        if ($this->user->getRole() === 'admin' && $this->countOtherActiveAdmins($this->user->getId()) === 0) {
            $errors['_delete'] = 'Vous êtes le dernier administrateur actif : créez-en un autre avant de supprimer ce compte.';
        }

        if ($errors !== []) {
            $this->showProfile($errors, null, 'delete');
            return;
        }

        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $this->user->getId()]);

        Security::destroySession();
        Security::restartSession();
        Flash::set('success', 'Votre compte a été supprimé.');
        $this->redirect('login');
    }
}
