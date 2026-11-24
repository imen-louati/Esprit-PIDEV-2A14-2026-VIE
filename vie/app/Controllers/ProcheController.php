<?php

namespace App\Controllers;

class ProcheController extends Controller
{
    protected string $page = 'proche';
    protected string $title = 'Espace proche | VIE';
    protected array $allowedRoles = ['proche'];

    public function index(): void
    {
        $patient = $this->user->getPatientId() > 0 ? $this->findUserById($this->user->getPatientId()) : null;

        $state = 'none';
        $daysLeft = null;

        if ($patient !== null) {
            $today = new \DateTimeImmutable('today');
            $expires = $this->user->getAccessExpiresAt();
            if ($this->user->getAccessRevokedAt() !== '') {
                $state = 'revoked';
            } elseif ($expires !== '' && $expires < $today->format('Y-m-d')) {
                $state = 'expired';
            } else {
                $state = 'ok';
                if ($expires !== '') {
                    $daysLeft = (int) $today->diff(new \DateTimeImmutable($expires))->days;
                }
            }
        }

        $this->render('proche', [
            'patient' => $patient,
            'state' => $state,
            'daysLeft' => $daysLeft,
        ]);
    }
}
