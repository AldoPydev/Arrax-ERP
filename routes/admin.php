<?php

// Importar Rutas 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\RolesController;
use App\Http\Controllers\admin\UserController;

//========= Rutas User
Route::resource('users',UserController::class);

//========= Rutas Permisos
Route::resource('permissions',PermissionController::class);

//========= Rutas Roles
Route::resource('roles',RolesController::class);