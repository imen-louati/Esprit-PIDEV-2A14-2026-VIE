<?php

namespace App\Controllers;

class NewsController extends Controller
{
    protected string $page = 'news';
    protected string $title = 'Actualités | VIE Admin';
    protected string $role = 'admin';

    public function index(): void
    {
        $this->render('news');
    }
}
