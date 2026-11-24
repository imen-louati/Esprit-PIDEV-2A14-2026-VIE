<?php

namespace App\Controllers;

class DoctorController extends Controller
{
    protected string $page = 'doctor';
    protected string $title = 'Espace médecin | VIE';
    protected array $allowedRoles = ['medecin'];

    public function index(): void
    {
        $stats = ['patients' => 0, 'doctors' => 0, 'newPatients' => 0];
        foreach ($this->db->query("SELECT role, COUNT(*) AS c FROM users WHERE status = 'active' AND role IN ('patient', 'medecin') GROUP BY role")->fetchAll() as $row) {
            if ($row['role'] === 'patient') {
                $stats['patients'] = (int) $row['c'];
            } else {
                $stats['doctors'] = (int) $row['c'];
            }
        }
        $stats['newPatients'] = (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'patient' AND created_at >= (NOW() - INTERVAL 7 DAY)")->fetchColumn();

        $this->render('doctor', [
            'stats' => $stats,
            'calendar' => $this->buildCalendar(),
        ]);
    }

    private function buildCalendar(): array
    {
        $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $first = new \DateTimeImmutable('first day of this month 00:00:00');
        $daysInMonth = (int) $first->format('t');
        $offset = (int) $first->format('N') - 1;
        $todayDay = (int) date('j');
        $cells = [];

        $previous = $first->modify('-' . $offset . ' days');
        for ($i = 0; $i < $offset; $i++) {
            $cells[] = ['day' => (int) $previous->modify('+' . $i . ' days')->format('j'), 'class' => 'day off'];
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $cells[] = ['day' => $d, 'class' => $d === $todayDay ? 'day today' : 'day'];
        }
        $trailing = (7 - (count($cells) % 7)) % 7;
        for ($i = 1; $i <= $trailing; $i++) {
            $cells[] = ['day' => $i, 'class' => 'day off'];
        }

        return [
            'label' => $months[(int) $first->format('n') - 1] . ' ' . $first->format('Y'),
            'cells' => $cells,
        ];
    }
}
