<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Controladores
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\isiPublicationsController;
use App\Http\Controllers\nonIsiPublicationsController;
use App\Http\Controllers\booksController;
use App\Http\Controllers\awardsController;
use App\Http\Controllers\organizationsScEventsController;
use App\Http\Controllers\participationsScEventsController;
use App\Http\Controllers\thesisStudentController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisosController;

// Rutas Generales (SYS_)

// Users
Route::apiResource('usuarios', UsuarioController::class);
Route::get('cambiarEstadoUsuario/{id}', [UsuarioController::class, 'cambiarEstadoUsuario']);
Route::get('researchers', [UsuarioController::class, 'getResearchers']);
Route::post('usuarios/updateUser/{id}', [UsuarioController::class, 'updateUser']);
// Route::put('/changePassword/{id}', [UsuarioController::class, 'changePassword']);
// Autentificación:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//IsiPublications
Route::apiResource('isiPublications', isiPublicationsController::class);

//nonIsiPublications
Route::apiResource('nonIsiPublications', nonIsiPublicationsController::class);

//Books
Route::apiResource('books', booksController::class);

//Awards
Route::apiResource('awards', awardsController::class);

//OrganizationScEvents
Route::apiResource('organizationScEvents', organizationsScEventsController::class);
Route::get('organizationDownload/{id}' , [organizationsScEventsController::class, 'organizationDownload']);
Route::post('organizationScEvents/addFile', [organizationsScEventsController::class, 'addFile']);

//participationScEvents
Route::apiResource('participationScEvents', participationsScEventsController::class);
Route::get('participationDownload/{id}' , [participationsScEventsController::class, 'participationDownload']);
Route::post('participationScEvents/addFile', [participationsScEventsController::class, 'addFile']);

//thesisStudents
Route::apiResource('thesisStudents', thesisStudentController::class);
Route::get('thesisDownload/{id}' , [thesisStudentController::class, 'thesisDownload']);

Route::apiResource('roles', RolController::class);
Route::apiResource('permisos', PermisosController::class);