<?php

namespace App\Controllers;

class ProcheController extends Controller
{
    protected string $page = 'proche';
    protected string $title = 'Espace proche | VIE';
    protected string $role = 'proche';

    public function index(): void
    {
        $this->render('proche');
    }
}
