<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\DoctorController;
use App\Controllers\PatientController;
use App\Controllers\ProcheController;
use App\Controllers\ProfileController;
use App\Controllers\UsersController;

return [
    'home' => [AuthController::class, 'home'],
    'login' => [AuthController::class, 'login'],
    'register' => [AuthController::class, 'register'],
    'logout' => [AuthController::class, 'logout'],
    'forgot-password' => [AuthController::class, 'forgotPassword'],
    'reset-password' => [AuthController::class, 'resetPassword'],

    'dashboard' => [DashboardController::class, 'index'],

    'users' => [UsersController::class, 'index'],
    'users-show' => [UsersController::class, 'show'],
    'users-create' => [UsersController::class, 'create'],
    'users-edit' => [UsersController::class, 'edit'],
    'users-delete' => [UsersController::class, 'delete'],
    'users-status' => [UsersController::class, 'setStatus'],

    'profile' => [ProfileController::class, 'index'],
    'profile-update' => [ProfileController::class, 'update'],
    'profile-password' => [ProfileController::class, 'changePassword'],
    'profile-delete' => [ProfileController::class, 'deleteAccount'],

    'doctor' => [DoctorController::class, 'index'],

    'patient' => [PatientController::class, 'index'],
    'patient-access' => [PatientController::class, 'updateAccess'],
    'patient-access-revoke' => [PatientController::class, 'toggleRevoke'],

    'proche' => [ProcheController::class, 'index'],
];
