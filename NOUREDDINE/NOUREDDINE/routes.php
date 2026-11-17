<?php
use App\Controllers\AgendaController;
use App\Controllers\CentersController;
use App\Controllers\DashboardController;
use App\Controllers\DoctorController;
use App\Controllers\NewsController;
use App\Controllers\PatientController;
use App\Controllers\ProcheController;
use App\Controllers\UsersController;

return [
    'dashboard' => [DashboardController::class, 'index'],
    'users' => [UsersController::class, 'index'],
    'centers' => [CentersController::class, 'index'],
    'news' => [NewsController::class, 'index'],
    'doctor' => [DoctorController::class, 'index'],
    'agenda' => [AgendaController::class, 'index'],
    'patient' => [PatientController::class, 'index'],
    'proche' => [ProcheController::class, 'index'],
];
