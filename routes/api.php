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
use App\Http\Controllers\indicatorsController;
use App\Http\Controllers\organizationsScEventsController;
use App\Http\Controllers\participationsScEventsController;
use App\Http\Controllers\scCollaborationsController;
use App\Http\Controllers\thesisStudentController;
use App\Http\Controllers\outreachActivitiesController;
use App\Http\Controllers\patentsController;
use App\Http\Controllers\publicPrivateController;
use App\Http\Controllers\technologyKnowledgeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\postDocController;
use App\Http\Controllers\researchLineController;
use App\Http\Controllers\roleUserController;
use App\Http\Controllers\universitiesController;
use App\Http\Controllers\recycleBinController;
use App\Http\Controllers\SessionLogController;
use App\Http\Controllers\announcementController;
use App\Http\Controllers\performanceModeController;
use App\Http\Controllers\progressReportGoalsController;
use App\Models\PerformanceDataCollectionMode;

// Rutas Generales (SYS_)

Route::group(['prefix' => 'recycle-bin'], function() {
    Route::get('/', [recycleBinController::class, 'index']); // Lista los registros eliminados
    Route::post('/restore', [recycleBinController::class, 'restore']);
    Route::post('/delete', [recycleBinController::class, 'forceDelete']);
});

// Users
Route::apiResource('usuarios', UsuarioController::class);
Route::get('cambiarEstadoUsuario/{id}', [UsuarioController::class, 'cambiarEstadoUsuario']);
Route::get('getResearchers2', [UsuarioController::class, 'getResearchers2']);
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
Route::post('importIsi', [isiPublicationsController::class, 'importIsi']);

//nonIsiPublications
Route::apiResource('nonIsiPublications', nonIsiPublicationsController::class);
Route::get('nonIsiDownload/{id}' , [nonIsiPublicationsController::class, 'nonIsiDownload']);
Route::post('nonIsiPublications/addFile', [nonIsiPublicationsController::class, 'addFile']);
Route::post('verifyNonIsi', [nonIsiPublicationsController::class, 'verifyNonIsi']);
Route::post('importNonIsi', [nonIsiPublicationsController::class, 'importNonIsi']);

//Books
Route::apiResource('books', booksController::class);
Route::post('verifyBook', [booksController::class, 'verifyBook']);
Route::post('importBook', [booksController::class, 'importBook']);

//Awards
Route::apiResource('awards', awardsController::class);
Route::post('verifyAward', [awardsController::class, 'verifyAward']);
Route::post('importAward', [awardsController::class, 'importAward']);

//OrganizationScEvents
Route::apiResource('organizationScEvents', organizationsScEventsController::class);
Route::get('organizationDownload/{id}' , [organizationsScEventsController::class, 'organizationDownload']);
Route::post('organizationScEvents/addFile', [organizationsScEventsController::class, 'addFile']);
Route::post('verifyOrganization', [organizationsScEventsController::class, 'verifyOrganization']);
Route::post('importOrganization', [organizationsScEventsController::class, 'importOrganization']);

//participationScEvents
Route::apiResource('participationScEvents', participationsScEventsController::class);
Route::get('participationDownload/{id}' , [participationsScEventsController::class, 'participationDownload']);
Route::post('participationScEvents/addFile', [participationsScEventsController::class, 'addFile']);
Route::post('verifyParticipation', [participationsScEventsController::class, 'verifyParticipation']);
Route::post('importParticipation', [participationsScEventsController::class, 'importParticipation']);

//scCollaborations
Route::apiResource('scCollaborations', scCollaborationsController::class);
Route::get('scCollaborationsActive/{id}', [scCollaborationsController::class, 'scCollaborationsActive']);
Route::post('verifyCollaboration', [scCollaborationsController::class, 'verifyCollaboration']);
Route::post('importCollaboration', [scCollaborationsController::class, 'importCollaboration']);

//conjointProjects
Route::apiResource('conjointProjects', conjointProjectController::class);
Route::post('verifyConjoint', [conjointProjectController::class, 'verifyConjoint']);
Route::get('conjointProjectsActive/{id}', [conjointProjectController::class, 'conjointProjectsActive']);

//thesisStudents
Route::apiResource('thesisStudents', thesisStudentController::class);
Route::get('thesisDownload/{id}' , [thesisStudentController::class, 'thesisDownload']);
Route::post('thesisStudents/addFile', [thesisStudentController::class, 'addFile']);
Route::post('verifyThesis', [thesisStudentController::class, 'verifyThesis']);
Route::post('importThesis', [thesisStudentController::class, 'importThesis']);

//postDoc
Route::apiResource('postDoc', postDocController::class);
Route::post('verifyPostdoc', [postDocController::class, 'verifyPostdoc']);
Route::post('importPostdoc', [postDocController::class, 'importPostdoc']);

//outreachActivities
Route::apiResource('outreachActivities', outreachActivitiesController::class);
Route::post('verifyOutreach', [outreachActivitiesController::class, 'verifyOutreach']);
Route::post('importOutreach', [outreachActivitiesController::class, 'importOutreach']);
//patents
Route::apiResource('patents', patentsController::class);
Route::post('verifyPatent', [patentsController::class, 'verifyPatent']);
Route::post('importPatent', [patentsController::class, 'importPatent']);

//publicPrivate
Route::apiResource('publicPrivate', publicPrivateController::class);
Route::get('publicPrivateDownload/{id}' , [publicPrivateController::class, 'publicPrivateDownload']);
Route::post('verifyPublicPrivate', [publicPrivateController::class, 'verifyPublicPrivate']);
Route::post('importPublicPrivate', [publicPrivateController::class, 'importPublicPrivate']);
Route::post('publicPrivate/addFile', [publicPrivateController::class, 'addFile']);

//technologyKnowledge
Route::apiResource('technologyKnowledge', technologyKnowledgeController::class);
Route::get('technologyKnowledgesActive/{id}', [technologyKnowledgeController::class, 'technologyKnowledgesActive']);
Route::post('verifyTechnology', [technologyKnowledgeController::class, 'verifyTechnology']);
Route::post('importTecAndKnow', [technologyKnowledgeController::class, 'importTecAndKnow']);

//fundingSources
Route::apiResource('fundingSources', fundingSourcesController::class);
Route::post('verifyFunding', [fundingSourcesController::class, 'verifyFunding']);
Route::post('importFunding', [fundingSourcesController::class, 'importFunding']);
Route::get('fundingSourcesActive/{id}', [fundingSourcesController::class, 'fundingSourcesActive']);

Route::apiResource('roles', RolController::class);
Route::apiResource('permisos', PermisosController::class);
Route::get('getPermisosRol/{id}', [PermisosController::class, 'getPermisosRol']);

Route::post('updateRecords', [thesisStudentController::class, 'updateRecords']);
//Universities

Route::apiResource('universities', universitiesController::class);

//Exports

Route::post('exportConsolidado', [exportsController::class, 'exportConsolidado']);

Route::post('exportIndividual', [exportsController::class, 'exportIndividual']);

Route::post('exportStatistics', [exportsController::class, 'exportStatistics']);

//Extra tables
Route::get('getUsersWithResearch', [UsuarioController::class, 'getUsersWithResearch']);
Route::post('editProgressReport', [extraTablesController::class, 'editProgressReport']);
Route::get('showProgressReport' , [extraTablesController::class, 'showProgressReport']);
Route::post('delete-records', [extraTablesController::class, 'deleteRecords']);
Route::get('/session-logs', [SessionLogController::class, 'getRecentSessions']);

//Indicators
Route::post('/saveProgressReport', [progressReportGoalsController::class, 'saveProgressReport']);
Route::get('/getProgressReport/{id}', [progressReportGoalsController::class, 'getProgressReport']);

Route::get('/getFilteredRecordsByModule', [IndicatorsController::class, 'getFilteredRecordsByModule']);
Route::get('/getPublicationsByResearchLine/{lineName}', [IndicatorsController::class, 'getPublicationsByResearchLine']);
Route::get('/getGeneralRecordsByProgressReport', [IndicatorsController::class, 'getGeneralRecordsByProgressReport']);
Route::get('/getMembersByLine/{lineName}', [indicatorsController::class, 'getMembersByLine']);
Route::get('/getPersonRecordsByProgressReport/{userId}', [IndicatorsController::class, 'getPersonRecordsByProgressReport']);
Route::get('getPublicationsByProgressReport' , [indicatorsController::class, 'getPublicationsByProgressReport']);
Route::get('getIndicators' , [indicatorsController::class, 'getIndicators']);
Route::get('getIndicators/{id}' , [indicatorsController::class, 'getIndicatorsByProgressYear']);

//Dashboard

// Obtener el estado actual del modo Performance Data Collection
Route::get('performance-mode', [performanceModeController::class, 'getMode']);

// Activar el modo Performance Data Collection
Route::post('performance-mode', [performanceModeController::class, 'activateMode']);

// Desactivar el modo Performance Data Collection
Route::delete('performance-mode', [performanceModeController::class, 'deactivateMode']);

Route::get('performance-mode/notify', [performanceModeController::class, 'notifyCompletion']);

Route::get('/active-announcement', [announcementController::class, 'getActiveAnnouncement']);
Route::apiResource('announcements', announcementController::class);
Route::get('update-research-lines' , [dashboardController::class, 'updateResearchLinesInvolved']);
Route::get('getRegistros/{cantidad}' , [dashboardController::class, 'getRegistros']);
Route::get('getRegistrosUser/{userId}/{cantidad}' , [dashboardController::class, 'getRegistrosUser']);
//Role User

Route::apiResource('rolesUser', roleUserController::class);

//Role User

Route::apiResource('researchLines', researchLineController::class);
