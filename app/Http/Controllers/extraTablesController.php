<?php

namespace App\Http\Controllers;

use App\Models\awards;
use App\Models\books;
use App\Models\extraTables;
use App\Models\fundingSources;
use App\Models\isiPublication;
use App\Models\nonIsiPublication;
use App\Models\organizationsScEvents;
use App\Models\outreachActivities;
use App\Models\participationScEvents;
use App\Models\patents;
use App\Models\postDoc;
use App\Models\publicPrivate;
use App\Models\scCollaborations;
use App\Models\SessionLog;
use App\Models\technologyKnowledge;
use App\Models\thesisStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class extraTablesController extends Controller
{
    public function editProgressReport(Request $request)
    {
        $input = $request->all();
        $progressReport = extraTables::where('name','progressReport');
        $progressReport->update($input);
        return response()->json("Progress report updated!");
    }

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function showProgressReport(Request $request)
    {
        $input = $request->all();
        $progressReport = extraTables::where('name','progressReport')->pluck('value');
        return response()->json($progressReport[0]);
    }

    public function deleteRecords(Request $request)
    {
        $module = $request->input('module');
        $ids = $request->input('ids');
        $userId = $request->input('user_id'); // Asegúrate de enviar el ID del usuario desde el frontend
    
        if (empty($ids)) {
            return response()->json(['error' => 'No records selected'], 400);
        }
    
        // Variable para almacenar el número de registros eliminados
        $deletedCount = 0;
    
        // Eliminar registros según el módulo
        switch ($module) {
            case 'thesis':
                $deletedCount = thesisStudent::whereIn('id', $ids)->delete();
                break;
            case 'wos-publications':
                $deletedCount = isiPublication::whereIn('id', $ids)->delete();
                break;
            case 'non-wos-publications':
                $deletedCount = nonIsiPublication::whereIn('id', $ids)->delete();
                break;
            case 'awards':
                $deletedCount = awards::whereIn('id', $ids)->delete();
                break;
            case 'books':
                $deletedCount = books::whereIn('id', $ids)->delete();
                break;
            case 'organization-sc':
                $deletedCount = organizationsScEvents::whereIn('id', $ids)->delete();
                break;
            case 'participation-sc':
                $deletedCount = participationScEvents::whereIn('id', $ids)->delete();
                break;
            case 'conjoint':
                $deletedCount = scCollaborations::whereIn('id', $ids)->delete();
                break;
            case 'funding':
                $deletedCount = fundingSources::whereIn('id', $ids)->delete();
                break;
            case 'outreach':
                $deletedCount = outreachActivities::whereIn('id', $ids)->delete();
                break;
            case 'patents':
                $deletedCount = patents::whereIn('id', $ids)->delete();
                break;
            case 'postdoc':
                $deletedCount = postDoc::whereIn('id', $ids)->delete();
                break;
            case 'public-private':
                $deletedCount = publicPrivate::whereIn('id', $ids)->delete();
                break;
            case 'technology-knowledge':
                $deletedCount = technologyKnowledge::whereIn('id', $ids)->delete();
                break;
            default:
                return response()->json(['error' => 'Invalid module'], 400);
        }
    
        // Registrar el evento en el log
        SessionLog::create([
            'user_id' => $userId,
            'event_type' => 'delete',
            'description' => "Usuario eliminó {$deletedCount} registros en el módulo {$module}",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
    
        return response()->json([
            'success' => 'Records deleted successfully',
            'deletedCount' => $deletedCount,
        ]);
    }
    

}
