<?php

namespace App\Controllers;

class DoctorController extends Controller
{
    protected string $page = 'doctor';
    protected string $title = 'Espace médecin | VIE';
    protected string $role = 'medecin';

    public function index(): void
    {
        $this->render('doctor');
    }
}
