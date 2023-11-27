<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\exportsController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\isiPublicationsController;
use App\Http\Controllers\nonIsiPublicationsController;
use App\Http\Controllers\booksController;
use App\Http\Controllers\awardsController;
use App\Http\Controllers\conjointProjectController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\extraTablesController;
use App\Http\Controllers\fundingSourcesController;
use App\Http\Controllers\organizationsScEventsController;
use App\Http\Controllers\participationsScEventsController;
use App\Http\Controllers\scCollaborationsController;
use App\Http\Controllers\thesisStudentController;
use App\Http\Controllers\outreachActivitiesController;
use App\Http\Controllers\patentsController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\postDocController;
use App\Http\Controllers\publicPrivateController;
use App\Http\Controllers\researchLineController;
use App\Http\Controllers\roleUserController;
use App\Http\Controllers\technologyKnowledgeController;

// Rutas Generales (SYS_)

// Users
Route::apiResource('usuarios', UsuarioController::class);
Route::get('cambiarEstadoUsuario/{id}', [UsuarioController::class, 'cambiarEstadoUsuario']);
Route::get('researchers', [UsuarioController::class, 'getResearchers']);
Route::post('usuarios/updateUser/{id}', [UsuarioController::class, 'updateUser']);
Route::put('/changePassword/{id}', [UsuarioController::class, 'changePassword']);
// Autentificación:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//IsiPublications
Route::apiResource('isiPublications', isiPublicationsController::class);
Route::post('useDoi', [isiPublicationsController::class, 'useDoi']);
Route::post('verifyIsi', [isiPublicationsController::class, 'verifyIsi']);

//nonIsiPublications
Route::apiResource('nonIsiPublications', nonIsiPublicationsController::class);
Route::get('nonIsiDownload/{id}' , [nonIsiPublicationsController::class, 'nonIsiDownload']);
Route::post('nonIsi/addFile', [nonIsiPublicationsController::class, 'addFile']);
Route::post('verifyNonIsi', [nonIsiPublicationsController::class, 'verifyNonIsi']);

//Books
Route::apiResource('books', booksController::class);
Route::post('verifyBook', [booksController::class, 'verifyBook']);

//Awards
Route::apiResource('awards', awardsController::class);
Route::post('verifyAward', [awardsController::class, 'verifyAward']);

//OrganizationScEvents
Route::apiResource('organizationScEvents', organizationsScEventsController::class);
Route::get('organizationDownload/{id}' , [organizationsScEventsController::class, 'organizationDownload']);
Route::post('organizationScEvents/addFile', [organizationsScEventsController::class, 'addFile']);
Route::post('verifyOrganization', [organizationsScEventsController::class, 'verifyOrganization']);

//participationScEvents
Route::apiResource('participationScEvents', participationsScEventsController::class);
Route::get('participationDownload/{id}' , [participationsScEventsController::class, 'participationDownload']);
Route::post('participationScEvents/addFile', [participationsScEventsController::class, 'addFile']);
Route::post('verifyParticipation', [participationsScEventsController::class, 'verifyParticipation']);

//scCollaborations
Route::apiResource('scCollaborations', scCollaborationsController::class);
Route::post('verifyCollaboration', [scCollaborationsController::class, 'verifyCollaboration']);

//conjointProjects
Route::apiResource('conjointProjects', conjointProjectController::class);
Route::post('verifyConjoint', [conjointProjectController::class, 'verifyConjoint']);

//thesisStudents
Route::apiResource('thesisStudents', thesisStudentController::class);
Route::get('thesisDownload/{id}' , [thesisStudentController::class, 'thesisDownload']);
Route::post('thesisStudents/addFile', [thesisStudentController::class, 'addFile']);
Route::post('verifyThesis', [thesisStudentController::class, 'verifyThesis']);

//postDoc
Route::apiResource('postDoc', postDocController::class);
Route::post('verifyPostdoc', [postDocController::class, 'verifyPostdoc']);

//outreachActivities
Route::apiResource('outreachActivities', outreachActivitiesController::class);
Route::post('verifyOutreach', [outreachActivitiesController::class, 'verifyOutreach']);

//patents
Route::apiResource('patents', patentsController::class);
Route::post('verifyPatent', [patentsController::class, 'verifyPatent']);

//publicPrivate
Route::apiResource('publicPrivate', publicPrivateController::class);
Route::post('verifyPublicPrivate', [publicPrivateController::class, 'verifyPublicPrivate']);

//technologyKnowledge
Route::apiResource('technologyKnowledge', technologyKnowledgeController::class);
Route::post('verifyTechnology', [technologyKnowledgeController::class, 'verifyTechnology']);

//fundingSources
Route::apiResource('fundingSources', fundingSourcesController::class);
Route::post('verifyFunding', [fundingSourcesController::class, 'verifyFunding']);

Route::apiResource('roles', RolController::class);
Route::apiResource('permisos', PermisosController::class);

//Exports

Route::post('exportConsolidado', [exportsController::class, 'exportConsolidado']);

Route::post('exportIndividual', [exportsController::class, 'exportIndividual']);

//Extra tables

Route::post('editProgressReport', [extraTablesController::class, 'editProgressReport']);
Route::get('showProgressReport' , [extraTablesController::class, 'showProgressReport']);

//Dashboard

Route::get('getRegistros/{cantidad}' , [dashboardController::class, 'getRegistros']);

//Role User

Route::apiResource('rolesUser', roleUserController::class);

//Role User

Route::apiResource('researchLines', researchLineController::class);
