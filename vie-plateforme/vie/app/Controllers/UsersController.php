<?php

namespace App\Controllers;

class UsersController extends Controller
{
    protected string $page = 'users';
    protected string $title = 'Utilisateurs | VIE Admin';
    protected string $role = 'admin';

    public function index(): void
    {
        $this->render('users');
    }
}
