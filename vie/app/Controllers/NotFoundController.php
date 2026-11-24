<?php

namespace App\Controllers;

class NotFoundController extends Controller
{
    protected string $page = 'not-found';
    protected string $title = 'Page introuvable | VIE';

    public function index(): void
    {
        http_response_code(404);

        if (\App\Core\Security::isAuthenticated()) {
            $user = $this->findUserById((int) $_SESSION['user_id']);
            if ($user !== null) {
                $this->user = $user;
                $this->render('not-found');
                return;
            }
        }

        $this->renderFront('not-found');
    }
}
