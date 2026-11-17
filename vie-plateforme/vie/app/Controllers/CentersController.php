<?php

namespace App\Controllers;

class CentersController extends Controller
{
    protected string $page = 'centers';
    protected string $title = 'Centres de soins | VIE Admin';
    protected string $role = 'admin';

    public function index(): void
    {
        $this->render('centers');
    }
}
