<?php
namespace App\Http\Controllers;

use App\Models\ProgressReportGoals;
use App\Models\researchLines;
use Illuminate\Http\Request;

class progressReportGoalsController extends Controller {
    public function saveProgressReport(Request $request) {
        $request->validate([
            'type' => 'required|string|in:RoleUser',
            'related_id' => 'required|integer',
            'goals' => 'required|array',
            'goals.*.progressReport' => 'integer|required',
            'goals.*.goal' => 'numeric|nullable',
            'module' => 'nullable|string' // Hacer que el campo 'module' sea opcional
        ]);
    
        // Convertir los objetivos en formato {año: meta}
        $formattedGoals = [];
        foreach ($request->goals as $report) {
            $formattedGoals[$report['progressReport']] = $report['goal'];
        }
    
        // Determinar el campo correcto según el tipo
        $query = ProgressReportGoals::query();
    
        if ($request->type === 'RoleUser') {
            $query->where('researcher_type_id', $request->related_id);
        }
    
        // Buscar si ya existe un registro
        $progressReport = $query->first();
    
        // Si existe, actualizar solo los goals y el campo module (si se pasó)
        if ($progressReport) {
            $dataToUpdate = [
                'goals' => json_encode($formattedGoals),
            ];
    
            // Solo actualizar el campo 'module' si está presente en la solicitud
            if ($request->has('module')) {
                $dataToUpdate['module'] = $request->module;
            }
    
            $progressReport->update($dataToUpdate);
        } else {
            // Si no existe, crearlo
            $dataToCreate = [
                'researcher_type_id' => $request->type === 'RoleUser' ? $request->related_id : null,
                'goals' => json_encode($formattedGoals),
            ];
    
            // Solo crear con el campo 'module' si está presente en la solicitud
            if ($request->has('module')) {
                $dataToCreate['module'] = $request->module;
            }
    
            $progressReport = ProgressReportGoals::create($dataToCreate);
        }
    
        return response()->json([
            'message' => 'Progress report saved successfully',
            'data' => $progressReport
        ], 200);
    }
    
    public function getProgressReport($id, Request $request) {
        $module = $request->query('module');  // Obtener el módulo desde la consulta
        
        // if ($type === 'ResearchLine' && is_string($id)) {
        //     $researchLine = researchLines::where('id', $id)->first();
        //     if (!$researchLine) {
        //         return response()->json([
        //             'message' => 'Research line not found',
        //             'data' => null
        //         ], 404);
        //     }
        //     $id = $researchLine->id;
        // }
    
        // Construir la consulta base para filtrar por 'research_line_id', 'researcher_type_id' o 'user_id'
        $query = ProgressReportGoals::where('researcher_type_id',$id);
        
        // Agregar el filtro de módulo si está presente
        if ($module != 'null') {
            $query->where('module', $module);
        }
    
        // Ejecutar la consulta
        $progressReport = $query->first();
        
        return response()->json([
            'message' => 'Progress report retrieved successfully',
            'data' => $progressReport
        ], 200);
    }
    
}
