<?php

namespace App\Controllers;

class DashboardController extends Controller
{
    protected string $page = 'dashboard';
    protected string $title = 'Tableau de bord | VIE Admin';
    protected array $allowedRoles = ['admin'];

    public function index(): void
    {
        $counts = ['patient' => 0, 'proche' => 0, 'medecin' => 0, 'admin' => 0];
        $pendingDoctors = 0;
        $blocked = 0;
        $total = 0;

        foreach ($this->db->query('SELECT role, status, COUNT(*) AS c FROM users GROUP BY role, status')->fetchAll() as $row) {
            $c = (int) $row['c'];
            $counts[$row['role']] += $c;
            $total += $c;
            if ($row['role'] === 'medecin' && $row['status'] === 'inactive') {
                $pendingDoctors += $c;
            }
            if ($row['status'] === 'blocked') {
                $blocked += $c;
            }
        }

        $monthNames = ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        $months = [];
        $cursor = new \DateTimeImmutable('first day of this month 00:00:00');
        for ($i = 5; $i >= 0; $i--) {
            $month = $cursor->modify('-' . $i . ' months');
            $months[$month->format('Y-m')] = ['label' => $monthNames[(int) $month->format('n') - 1], 'count' => 0];
        }
        $stmt = $this->db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c FROM users WHERE created_at >= :since GROUP BY ym");
        $stmt->execute(['since' => $cursor->modify('-5 months')->format('Y-m-d H:i:s')]);
        foreach ($stmt->fetchAll() as $row) {
            if (isset($months[$row['ym']])) {
                $months[$row['ym']]['count'] = (int) $row['c'];
            }
        }
        $maxMonth = max(1, max(array_column($months, 'count')));
        foreach ($months as $key => $month) {
            $months[$key]['height'] = $month['count'] === 0 ? 4 : max(10, (int) round($month['count'] / $maxMonth * 100));
        }

        $latestUsers = $this->db->query('SELECT id, first_name, last_name, email, role, status, created_at FROM users ORDER BY id DESC LIMIT 6')->fetchAll();

        $pending = $this->db->query("SELECT id, first_name, last_name, email, speciality, license_number, city
            FROM users WHERE role = 'medecin' AND status = 'inactive' ORDER BY id ASC LIMIT 5")->fetchAll();

        $recentLogins = $this->db->query('SELECT id, first_name, last_name, role, last_login_at
            FROM users WHERE last_login_at IS NOT NULL ORDER BY last_login_at DESC LIMIT 6')->fetchAll();

        $donut = '#E6ECF6 0 100%';
        if ($total > 0) {
            $colors = ['patient' => '#C2186A', 'proche' => '#2C92E5', 'medecin' => '#6E4BD8', 'admin' => '#1B3B5A'];
            $start = 0.0;
            $parts = [];
            foreach ($colors as $role => $color) {
                $share = $counts[$role] / $total * 100;
                if ($share > 0) {
                    $end = $start + $share;
                    $parts[] = $color . ' ' . round($start, 2) . '% ' . round($end, 2) . '%';
                    $start = $end;
                }
            }
            $donut = implode(', ', $parts);
        }

        $this->render('dashboard', [
            'counts' => $counts,
            'total' => $total,
            'pendingDoctors' => $pendingDoctors,
            'blocked' => $blocked,
            'months' => $months,
            'latestUsers' => $latestUsers,
            'pending' => $pending,
            'recentLogins' => $recentLogins,
            'donut' => $donut,
        ]);
    }
}
