<?php

namespace App\Controllers;

abstract class Controller
{
    protected string $page = 'dashboard';
    protected string $title = 'VIE';
    protected string $role = 'admin';

    protected array $demoUsers = [
        'admin' => ['name' => 'Administrateur VIE', 'email' => 'admin@vie.tn'],
        'medecin' => ['name' => 'Dr Ben Salah', 'email' => 'bensalah@vie.tn'],
        'patient' => ['name' => 'Sarra Trabelsi', 'email' => 'sarra.trabelsi@vie.tn'],
        'proche' => ['name' => 'Mehdi Trabelsi', 'email' => 'mehdi.trabelsi@vie.tn'],
    ];

    public function __construct()
    {
        $this->authorize();
    }

    protected function authorize(): void
    {
        $config = require __DIR__ . '/../../config.php';
        if (!empty($config['demo'])) {
            return;
        }
        $user = $_SESSION['user'] ?? null;
        if ($user === null || $user['role'] !== $this->role) {
            header('Location: login.php');
            exit;
        }
    }

    protected function currentUser(): array
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if ($sessionUser !== null && $sessionUser['role'] === $this->role) {
            return $sessionUser;
        }
        $demo = $this->demoUsers[$this->role];
        return ['name' => $demo['name'], 'email' => $demo['email'], 'role' => $this->role];
    }

    protected function capture(string $file, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $content = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data);
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data)
            : '';

        $title = $this->title;
        $currentPage = $this->page;
        $role = $this->role;
        $user = $this->currentUser();

        require_once __DIR__ . '/../Views/layout.php';
    }
}
