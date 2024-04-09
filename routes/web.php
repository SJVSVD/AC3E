<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authorize;
// Controladores
use App\Http\Controllers\PageController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPassword;

// Rutas Base:
Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('email/{email}', [UsuarioController::class, 'email'])->name('email');
// **************************************************************************************************

// Rutas Protegidas:
Route::group(['middleware' => 'auth'], function () {
	//Route::group(['middleware' => ['role:Colaborador']], function () {
		// Sistemas:
		Route::group(['middleware' => ['role:Administrator']], function () {
			Route::get('/admin-roles', [PageController::class, 'adminRoles'])->name('admin-roles');
			
		});
		Route::get('/indicators', [PageController::class, 'indicators'])->name('indicators');
		Route::get('/power-bi', [PageController::class, 'powerBi'])->name('power-bi');
		Route::get('/admin-usuarios', [PageController::class, 'adminUsuarios'])->name('admin-usuarios');
		Route::get('/import-info', [PageController::class, 'importInfo'])->name('import-info');
		Route::get('/isi-publications', [PageController::class, 'isiPublications'])->name('isi-publications');
		Route::get('/non-isi-publications', [PageController::class, 'nonIsiPublications'])->name('non-isi-publications');
		Route::get('/books', [PageController::class, 'books'])->name('books');
		Route::get('/awards', [PageController::class, 'awards'])->name('awards');
		Route::get('/organization-sc-events', [PageController::class, 'organizationScEvents'])->name('organization-sc-events');
		Route::get('/participation-sc-events', [PageController::class, 'participationScEvents'])->name('participation-sc-events');
		Route::get('/sc-collaborations', [PageController::class, 'scCollaborations'])->name('sc-collaborations');
		Route::get('/conjoint-projects', [PageController::class, 'conjointProjects'])->name('conjoint-projects');
		Route::get('/thesis-students', [PageController::class, 'thesisStudents'])->name('thesis-publications');
		Route::get('/outreach-activities', [PageController::class, 'outreachActivities'])->name('outreach-activities');
		Route::get('/patents', [PageController::class, 'patents'])->name('patents');
		Route::get('/public-private', [PageController::class, 'publicPrivate'])->name('public-private');
		Route::get('/technology-knowledge', [PageController::class, 'technologyKnowledge'])->name('technology-knowledge');
		Route::get('/post-doc', [PageController::class, 'postDoc'])->name('post-doc');
		Route::get('/funding-sources', [PageController::class, 'fundingSources'])->name('funding-sources');
		// ##################################################################################################################################
		// Otras:
		Route::get('/profile', [PageController::class, 'userProfile'])->name('profile');
		Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
		Route::get('/{page}', [PageController::class, 'index'])->name('page');
	//});

	// Accesos EXCLUSIVOS para invitados:
	Route::group(['middleware' => ['role:Invitado']], function () {
	});
});