<?php

namespace App\Controllers;

use App\Core\Flash;
use App\Core\Validator;

class PatientController extends Controller
{
    protected string $page = 'patient';
    protected string $title = 'Mon suivi | VIE';
    protected array $allowedRoles = ['patient'];

    public function index(): void
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, phone, relationship, share_appointments, share_medications, share_documents,
                access_expires_at, access_revoked_at
            FROM users WHERE role = 'proche' AND patient_id = :patient_id ORDER BY id ASC");
        $stmt->execute(['patient_id' => $this->user->getId()]);
        $proches = $stmt->fetchAll();

        $today = date('Y-m-d');
        $activeProche = null;
        foreach ($proches as $proche) {
            $expired = $proche['access_expires_at'] !== null && $proche['access_expires_at'] < $today;
            if ($proche['access_revoked_at'] === null && !$expired) {
                $activeProche = $proche;
                break;
            }
        }

        $age = null;
        if ($this->user->getBirthDate() !== '') {
            $age = (int) (new \DateTimeImmutable($this->user->getBirthDate()))->diff(new \DateTimeImmutable('today'))->y;
        }

        $this->render('patient', [
            'proches' => $proches,
            'activeProche' => $activeProche,
            'today' => $today,
            'age' => $age,
        ]);
    }

    private function ownedProcheId(): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = :id AND role = 'proche' AND patient_id = :patient_id LIMIT 1");
        $stmt->execute(['id' => (int) $this->post('proche_id'), 'patient_id' => $this->user->getId()]);
        $id = $stmt->fetchColumn();
        return $id === false ? null : (int) $id;
    }

    public function updateAccess(): void
    {
        $this->requirePost();
        if (!$this->csrfValid()) {
            Flash::set('error', 'Jeton de sécurité invalide.');
            $this->redirect('patient');
        }

        $procheId = $this->ownedProcheId();
        if ($procheId === null) {
            Flash::set('error', 'Ce proche n\'est pas lié à votre compte.');
            $this->redirect('patient');
        }

        $expires = $this->post('access_expires_at');
        if ($expires !== '' && (Validator::date($expires) !== null || $expires < date('Y-m-d'))) {
            Flash::set('error', 'La date de fin d\'accès doit être une date valide, aujourd\'hui ou plus tard.');
            $this->redirect('patient');
        }

        $stmt = $this->db->prepare('UPDATE users SET share_appointments = :a, share_medications = :m, share_documents = :d, access_expires_at = :expires WHERE id = :id');
        $stmt->execute([
            'a' => $this->post('share_appointments') === '1' ? 1 : 0,
            'm' => $this->post('share_medications') === '1' ? 1 : 0,
            'd' => $this->post('share_documents') === '1' ? 1 : 0,
            'expires' => $this->nullIfEmpty($expires),
            'id' => $procheId,
        ]);
        Flash::set('success', 'Les autorisations de votre proche ont été mises à jour.');
        $this->redirect('patient');
    }

    public function toggleRevoke(): void
    {
        $this->requirePost();
        if (!$this->csrfValid()) {
            Flash::set('error', 'Jeton de sécurité invalide.');
            $this->redirect('patient');
        }

        $procheId = $this->ownedProcheId();
        if ($procheId === null) {
            Flash::set('error', 'Ce proche n\'est pas lié à votre compte.');
            $this->redirect('patient');
        }

        $revoke = $this->post('do') === 'revoke';
        $stmt = $this->db->prepare($revoke ? 'UPDATE users SET access_revoked_at = NOW() WHERE id = :id' : 'UPDATE users SET access_revoked_at = NULL WHERE id = :id');
        $stmt->execute(['id' => $procheId]);
        Flash::set('success', $revoke ? 'L\'accès de votre proche a été révoqué.' : 'L\'accès de votre proche a été rétabli.');
        $this->redirect('patient');
    }
}
