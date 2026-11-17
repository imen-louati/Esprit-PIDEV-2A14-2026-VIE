<?php

namespace App\Controllers;

class NotFoundController extends Controller
{
    protected string $page = 'not-found';
    protected string $title = 'Page introuvable | VIE';
    protected string $role = 'admin';

    public function index(): void
    {
        $this->render('not-found');
    }
}
