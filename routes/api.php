<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Controladores
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\isiPublicationsController;
use App\Http\Controllers\thesisStudentController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisosController;

// Rutas Generales (SYS_)

// Users
Route::apiResource('usuarios', UsuarioController::class);
Route::get('cambiarEstadoUsuario/{id}', [UsuarioController::class, 'cambiarEstadoUsuario']);
Route::post('usuarios/updateUser/{id}', [UsuarioController::class, 'updateUser']);
// Autentificación:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//IsiPublications
Route::apiResource('isiPublications', isiPublicationsController::class);

//thesisStudents
Route::apiResource('thesisStudents', thesisStudentController::class);
Route::get('thesisDownload/{id}' , [thesisStudentController::class, 'thesisDownload']);

Route::apiResource('roles', RolController::class);
Route::apiResource('permisos', PermisosController::class);