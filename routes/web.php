<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//========= Importar controlador Dashboard
use App\Http\Controllers\Dashboard;

Route::get('/', function () {
    return view('auth.login');
});

//========= Autenticación de rutas (login, register, logout)
Auth::routes();

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.index');
})->name('dashboard.index');

//========= Protección de rutas con autenticación 
Route::middleware('auth')->group(function () {
    Route::resource('dashboard', Dashboard::class);
});