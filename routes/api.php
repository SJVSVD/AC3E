<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Controladores
use App\Http\Controllers\UsuarioController;

// Rutas Generales (SYS_)
// Users
Route::apiResource('usuarios', UsuarioController::class);

// Autentificación:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});