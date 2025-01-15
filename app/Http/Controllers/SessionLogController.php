<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use Illuminate\Http\Request;

class SessionLogController extends Controller {
    public function getRecentSessions(Request $request)
    {
        // Obtener los parámetros de fecha desde la solicitud
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');
    
        // Crear la consulta base
        $query = SessionLog::with('user')->orderBy('timestamp', 'desc');
    
        // Filtrar por rango de fechas si se proporcionan
        if ($startDate && $endDate) {
            $query->whereBetween('timestamp', [$startDate, $endDate]);
        } else {
            // Por defecto, filtrar los últimos 5 días
            $query->where('timestamp', '>=', now()->subDays(5));
        }
    
        // Ejecutar la consulta y limitar los resultados
        $recentSessions = $query->get();
    
        return response()->json($recentSessions);
    }
    
}
