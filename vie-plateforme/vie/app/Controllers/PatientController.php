<?php

namespace App\Controllers;

class PatientController extends Controller
{
    protected string $page = 'patient';
    protected string $title = 'Mon espace | VIE Patient';
    protected string $role = 'patient';

    public function index(): void
    {
        $this->render('patient');
    }
}
