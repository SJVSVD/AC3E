<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceDataCollectionMode;
use App\Models\SessionLog;
use App\Models\User;
use Carbon\Carbon;

class PerformanceModeController extends Controller
{
    public function notifyCompletion(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Obtener user_id desde query params en una solicitud GET
        $userId = $request->query('user_id'); 
        $user = User::findOrFail($userId);
    
        $user->update([
            'notified_complete' => true,
            'deadline' => Carbon::now(), // Guarda la fecha y hora actual
        ]);

        SessionLog::create([
            'user_id' => $userId,
            'event_type' => 'Notify',
            'description' => "Usuario con ID {$userId} notificó el registro en el módulo Performance Data Collection",
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
    
        return response()->json([
            'message' => 'Notification saved successfully',
            'user' => $user
        ]);
    }

    public function getMode(Request $request)
    {
        // Obtener el estado actual del modo Performance Data Collection
        $mode = PerformanceDataCollectionMode::latest()->first();
        
        if ($mode && $mode->is_active) {
            // Verificar si el parámetro 'user_id' fue enviado
            $userId = $request->input('user_id');
            
            // Si 'user_id' es proporcionado, se realiza la verificación adicional
            if ($userId) {
                // Buscar el usuario usando el user_id
                $user = User::find($userId);
                
                if ($user) {
                    // Verificar si el usuario es un investigador (idRole entre 1 y 3)
                    $isResearcher = in_array($user->idRole, [1, 2, 3]);
    
                    // Verificar si el campo 'notified_complete' está en 1 o 0
                    $hasNotifiedComplete = $user->notified_complete == 1;
    
                    // Devolver la respuesta con los campos adicionales
                    return response()->json([
                        'mode' => $mode,
                        'notify' => $hasNotifiedComplete,
                        'researcher' => $isResearcher
                    ]);
                } else {
                    return response()->json(['message' => 'User not found'], 404);
                }
            } else {
                // Si no se proporciona 'user_id', solo devolver el 'mode'
                return response()->json([
                    'mode' => $mode
                ]);
            }
        }
        
        return response()->json(['message' => 'No active performance mode'], 404);
    }

    // Activar el modo Performance Data Collection
    public function activateMode(Request $request)
    {
        $request->validate([
            'deadline' => 'required|date',
        ]);

        // Desactivar el modo activo anterior, si existe
        PerformanceDataCollectionMode::where('is_active', true)->update(['is_active' => false]);

        User::whereNotNull('deadline')->update([
            'deadline' => null,
            'notified_complete' => false,
        ]);

        // Crear un nuevo modo activo
        $mode = PerformanceDataCollectionMode::create([
            'is_active' => true,
            'deadline' => $request->deadline,
        ]);

        return response()->json($mode, 201);
    }

    // Desactivar el modo Performance Data Collection
    public function deactivateMode()
    {
        $mode = PerformanceDataCollectionMode::where('is_active', true)->first();

        if ($mode) {
            $mode->update(['is_active' => false]);
            return response()->json(['message' => 'Performance mode deactivated successfully']);
        }

        return response()->json(['message' => 'No active performance mode to deactivate'], 404);
    }
}
