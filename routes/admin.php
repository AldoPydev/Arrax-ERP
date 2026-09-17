<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RolesController;

// Importar Rutas Controladores
use App\Http\Controllers\admin\UserController;

//========= Rutas User
Route::resource('users',UserController::class);


//========= Rutas Roles
Route::resource('roles',RolesController::class);