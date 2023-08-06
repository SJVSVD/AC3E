<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authorize;
// Controladores
use App\Http\Controllers\PageController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;

// Rutas Base:
Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('email/{email}', [UsuarioController::class, 'email'])->name('email');

// **************************************************************************************************

// Rutas Protegidas:
Route::group(['middleware' => 'auth'], function () {
	//Route::group(['middleware' => ['role:Colaborador']], function () {
		// Sistemas:
		Route::group(['middleware' => ['role:Administrador']], function () {
			Route::get('/admin-roles', [PageController::class, 'adminRoles'])->name('admin-roles');

		});
		Route::get('/admin-usuarios', [PageController::class, 'adminUsuarios'])->name('admin-usuarios');
		Route::get('/isi-publications', [PageController::class, 'isiPublications'])->name('isi-publications');
		Route::get('/thesis-students', [PageController::class, 'thesisStudents'])->name('thesis-publications');
		// ##################################################################################################################################
		// Otras:
		Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
		Route::get('/{page}', [PageController::class, 'index'])->name('page');
	//});

	// Accesos EXCLUSIVOS para invitados:
	Route::group(['middleware' => ['role:Invitado']], function () {
	});
});