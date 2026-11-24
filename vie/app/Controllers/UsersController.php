<?php

namespace App\Controllers;

use App\Core\Flash;
use App\Core\Security;
use App\Core\Validator;
use App\Models\UserModel;

class UsersController extends Controller
{
    protected string $page = 'users';
    protected string $title = 'Utilisateurs | VIE Admin';
    protected array $allowedRoles = ['admin'];

    private const PER_PAGE = 10;

    public function index(): void
    {
        $q = $this->get('q');
        $role = $this->get('role');
        $status = $this->get('status');
        $pageNumber = max(1, (int) $this->get('p'));

        if (!in_array($role, Validator::ROLES, true)) {
            $role = '';
        }
        if (!in_array($status, Validator::STATUSES, true)) {
            $status = '';
        }

        $where = [];
        $params = [];
        if ($q !== '') {
            $where[] = "(CONCAT(u.first_name, ' ', u.last_name) LIKE :q_name OR u.email LIKE :q_email)";
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $params['q_name'] = $like;
            $params['q_email'] = $like;
        }
        if ($role !== '') {
            $where[] = 'u.role = :role';
            $params['role'] = $role;
        }
        if ($status !== '') {
            $where[] = 'u.status = :status';
            $params['status'] = $status;
        }
        $whereSql = $where === [] ? '' : ' WHERE ' . implode(' AND ', $where);

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users u' . $whereSql);
        $stmt->execute($params);
        $totalRows = (int) $stmt->fetchColumn();
        $pages = max(1, (int) ceil($totalRows / self::PER_PAGE));
        $pageNumber = min($pageNumber, $pages);
        $offset = ($pageNumber - 1) * self::PER_PAGE;

        $stmt = $this->db->prepare(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.role, u.status, u.speciality, u.city, u.last_login_at, u.created_at
             FROM users u' . $whereSql . ' ORDER BY u.id DESC LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', self::PER_PAGE, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $counts = ['patient' => 0, 'proche' => 0, 'medecin' => 0, 'admin' => 0];
        $pending = 0;
        foreach ($this->db->query('SELECT role, status, COUNT(*) AS c FROM users GROUP BY role, status')->fetchAll() as $row) {
            $counts[$row['role']] += (int) $row['c'];
            if ($row['status'] === 'inactive') {
                $pending += (int) $row['c'];
            }
        }

        $this->render('users', [
            'rows' => $rows,
            'filters' => ['q' => $q, 'role' => $role, 'status' => $status],
            'totalRows' => $totalRows,
            'pageNumber' => $pageNumber,
            'pages' => $pages,
            'counts' => $counts,
            'pendingCount' => $pending,
        ]);
    }

    public function show(): void
    {
        $target = $this->findUserById((int) $this->get('id'));
        if ($target === null) {
            Flash::set('error', 'Utilisateur introuvable.');
            $this->redirect('users');
        }

        $linked = [];
        $patient = null;

        if ($target->getRole() === 'patient') {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, relationship, access_revoked_at, access_expires_at
                FROM users WHERE role = 'proche' AND patient_id = :id ORDER BY id");
            $stmt->execute(['id' => $target->getId()]);
            $linked = $stmt->fetchAll();
        } elseif ($target->getRole() === 'proche' && $target->getPatientId() > 0) {
            $patient = $this->findUserById($target->getPatientId());
        }

        $this->title = $target->getFullName() . ' | VIE Admin';
        $this->render('user-show', ['target' => $target, 'linked' => $linked, 'patient' => $patient]);
    }

    public function create(): void
    {
        $form = $this->blankForm();
        $errors = [];

        if (Security::isPost()) {
            $form = $this->readForm();
            if (!$this->csrfValid()) {
                $errors['_form'] = 'Jeton de sécurité invalide. Rechargez la page.';
            } else {
                $errors = $this->validateForm($form, null);
                if ($errors === []) {
                    $newId = $this->saveNew($form, $errors);
                    if ($errors === [] && $newId > 0) {
                        Flash::set('success', 'Utilisateur « ' . $form['first_name'] . ' ' . $form['last_name'] . ' » créé avec succès.');
                        $this->redirect('users-show', ['id' => $newId]);
                    }
                }
            }
        }

        $this->title = 'Nouvel utilisateur | VIE Admin';
        $this->render('user-form', $this->formData('create', $form, $errors, null));
    }

    public function edit(): void
    {
        $target = $this->findUserById((int) $this->get('id'));
        if ($target === null) {
            Flash::set('error', 'Utilisateur introuvable.');
            $this->redirect('users');
        }

        $form = $this->formFromUser($target);
        $errors = [];

        if (Security::isPost()) {
            $form = $this->readForm();
            if ($target->getId() === $this->user->getId()) {
                $form['role'] = $target->getRole();
                $form['status'] = $target->getStatus();
            }
            if (!$this->csrfValid()) {
                $errors['_form'] = 'Jeton de sécurité invalide. Rechargez la page.';
            } else {
                $errors = $this->validateForm($form, $target);
                if ($errors === []) {
                    $this->saveExisting($target, $form, $errors);
                    if ($errors === []) {
                        Flash::set('success', 'Modifications enregistrées pour « ' . $form['first_name'] . ' ' . $form['last_name'] . ' ».');
                        $this->redirect('users-show', ['id' => $target->getId()]);
                    }
                }
            }
        }

        $this->title = 'Modifier ' . $target->getFullName() . ' | VIE Admin';
        $this->render('user-form', $this->formData('edit', $form, $errors, $target));
    }

    public function delete(): void
    {
        $this->requirePost();
        if (!$this->csrfValid()) {
            Flash::set('error', 'Jeton de sécurité invalide.');
            $this->redirect('users');
        }

        $target = $this->findUserById((int) $this->post('id'));
        if ($target === null) {
            Flash::set('error', 'Utilisateur introuvable.');
            $this->redirect('users');
        }
        if ($target->getId() === $this->user->getId()) {
            Flash::set('error', 'Vous ne pouvez pas supprimer votre propre compte depuis cette page. Utilisez « Profil ».');
            $this->redirect('users');
        }
        if ($target->getRole() === 'admin' && $target->getStatus() === 'active' && $this->countOtherActiveAdmins($target->getId()) === 0) {
            Flash::set('error', 'Impossible de supprimer le dernier administrateur actif.');
            $this->redirect('users');
        }

        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $target->getId()]);
        Flash::set('success', 'Utilisateur « ' . $target->getFullName() . ' » supprimé.');
        $this->redirect('users');
    }

    public function setStatus(): void
    {
        $this->requirePost();
        $return = $this->post('return') === 'dashboard' ? 'dashboard' : 'users';

        if (!$this->csrfValid()) {
            Flash::set('error', 'Jeton de sécurité invalide.');
            $this->redirect($return);
        }

        $target = $this->findUserById((int) $this->post('id'));
        $action = $this->post('do');
        if ($target === null || !in_array($action, ['validate', 'block', 'unblock'], true)) {
            Flash::set('error', 'Action impossible.');
            $this->redirect($return);
        }

        if ($action === 'validate') {
            if ($target->getStatus() !== 'inactive') {
                Flash::set('warning', 'Ce compte n\'est pas en attente de validation.');
                $this->redirect($return);
            }
            $newStatus = 'active';
            $message = 'Compte de « ' . $target->getFullName() . ' » validé.';
        } elseif ($action === 'block') {
            if ($target->getId() === $this->user->getId()) {
                Flash::set('error', 'Vous ne pouvez pas bloquer votre propre compte.');
                $this->redirect($return);
            }
            if ($target->getRole() === 'admin' && $this->countOtherActiveAdmins($target->getId()) === 0) {
                Flash::set('error', 'Impossible de bloquer le dernier administrateur actif.');
                $this->redirect($return);
            }
            $newStatus = 'blocked';
            $message = 'Compte de « ' . $target->getFullName() . ' » bloqué.';
        } else {
            $newStatus = 'active';
            $message = 'Compte de « ' . $target->getFullName() . ' » débloqué.';
        }

        $stmt = $this->db->prepare('UPDATE users SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $newStatus, 'id' => $target->getId()]);
        Flash::set('success', $message);
        $this->redirect($return);
    }

    private function blankForm(): array
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'role' => 'patient',
            'status' => 'active',
            'password' => '',
            'password_confirm' => '',
            'birth_date' => '',
            'cancer_type' => '',
            'stage' => '',
            'progress' => '',
            'speciality' => '',
            'license_number' => '',
            'city' => '',
            'patient_code' => '',
            'relationship' => '',
        ];
    }

    private function readForm(): array
    {
        $form = $this->blankForm();
        foreach (array_keys($form) as $key) {
            $form[$key] = in_array($key, ['password', 'password_confirm'], true) ? $this->rawPost($key) : $this->post($key);
        }
        $form['email'] = strtolower($form['email']);
        $form['patient_code'] = strtoupper($form['patient_code']);
        return $form;
    }

    private function formFromUser(UserModel $target): array
    {
        $form = $this->blankForm();
        $form['first_name'] = $target->getFirstName();
        $form['last_name'] = $target->getLastName();
        $form['email'] = $target->getEmail();
        $form['phone'] = $target->getPhone();
        $form['role'] = $target->getRole();
        $form['status'] = $target->getStatus();
        $form['birth_date'] = $target->getBirthDate();
        $form['cancer_type'] = $target->getCancerType();
        $form['stage'] = $target->getStage();
        $form['progress'] = $target->getProgress();
        $form['speciality'] = $target->getSpeciality();
        $form['license_number'] = $target->getLicenseNumber();
        $form['city'] = $target->getCity();
        $form['relationship'] = $target->getRelationship();

        if ($target->getRole() === 'proche' && $target->getPatientId() > 0) {
            $patient = $this->findUserById($target->getPatientId());
            if ($patient !== null) {
                $form['patient_code'] = $patient->getShareCode();
            }
        }
        return $form;
    }

    private function formData(string $mode, array $form, array $errors, ?UserModel $target): array
    {
        return [
            'mode' => $mode,
            'form' => $form,
            'errors' => $errors,
            'target' => $target,
            'isSelf' => $target !== null && $target->getId() === $this->user->getId(),
            'specialities' => Validator::SPECIALITIES,
            'relationships' => Validator::RELATIONSHIPS,
            'cancerTypes' => Validator::CANCER_TYPES,
            'stages' => Validator::STAGES,
        ];
    }

    private function validateForm(array $form, ?UserModel $existing): array
    {
        $errors = [];
        $existingId = $existing !== null ? $existing->getId() : 0;
        $isSelf = $existing !== null && $existingId === $this->user->getId();

        if (($m = Validator::name($form['first_name'], 'Prénom')) !== null) {
            $errors['first_name'] = $m;
        }
        if (($m = Validator::name($form['last_name'], 'Nom')) !== null) {
            $errors['last_name'] = $m;
        }
        if (($m = Validator::email($form['email'])) !== null) {
            $errors['email'] = $m;
        } elseif ($this->emailExists($form['email'], $existingId)) {
            $errors['email'] = 'Cette adresse e-mail est déjà utilisée.';
        }
        if (($m = Validator::phone($form['phone'])) !== null) {
            $errors['phone'] = $m;
        }
        if (!in_array($form['role'], Validator::ROLES, true)) {
            $errors['role'] = 'Rôle invalide.';
        }
        if (!in_array($form['status'], Validator::STATUSES, true)) {
            $errors['status'] = 'Statut invalide.';
        }

        if ($existing === null || $form['password'] !== '' || $form['password_confirm'] !== '') {
            if (($m = Validator::password($form['password'])) !== null) {
                $errors['password'] = $m;
            } elseif ($form['password'] !== $form['password_confirm']) {
                $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
            }
        }

        if (!isset($errors['role'])) {
            $errors += $this->validateRoleFields($form['role'], $form, $existingId);
        }

        if ($existing !== null) {
            if ($isSelf && ($form['role'] !== $existing->getRole() || $form['status'] !== 'active')) {
                $errors['role'] = 'Vous ne pouvez pas modifier votre propre rôle ou statut.';
            }
            $losesAdmin = $existing->getRole() === 'admin' && $existing->getStatus() === 'active'
                && ($form['role'] !== 'admin' || $form['status'] !== 'active');
            if ($losesAdmin && $this->countOtherActiveAdmins($existingId) === 0) {
                $errors['role'] = 'Il doit rester au moins un administrateur actif.';
            }
        }

        return $errors;
    }

    private function commonColumns(array $form): array
    {
        return [
            'first_name' => $form['first_name'],
            'last_name' => $form['last_name'],
            'email' => $form['email'],
            'phone' => $this->nullIfEmpty($form['phone']),
            'role' => $form['role'],
            'status' => $form['status'],
        ];
    }

    private function saveNew(array $form, array &$errors): int
    {
        try {
            $this->db->beginTransaction();
            $common = $this->commonColumns($form);
            $common['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
            $id = $this->insertUser($common, $form);
            $this->db->commit();
            return $id;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Create user failed: ' . $e->getMessage());
            $errors['_form'] = 'Erreur lors de la création de l\'utilisateur.';
            return 0;
        }
    }

    private function saveExisting(UserModel $target, array $form, array &$errors): void
    {
        try {
            $this->db->beginTransaction();
            $common = $this->commonColumns($form);
            if ($form['password'] !== '') {
                $common['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
            }
            $this->updateUser($target, $common, $form);
            $this->db->commit();
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Update user failed: ' . $e->getMessage());
            $errors['_form'] = 'Erreur lors de la modification de l\'utilisateur.';
        }
    }
}
