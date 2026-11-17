<?php

namespace App\Controllers;

class AgendaController extends Controller
{
    protected string $page = 'agenda';
    protected string $title = 'Agenda | VIE Médecin';
    protected string $role = 'medecin';

    public function index(): void
    {
        $this->render('agenda');
    }
}
