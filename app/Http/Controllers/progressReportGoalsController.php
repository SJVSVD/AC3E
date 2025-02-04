<?php
namespace App\Http\Controllers;

use App\Models\ProgressReportGoals;
use App\Models\researchLines;
use Illuminate\Http\Request;

class progressReportGoalsController extends Controller {
    public function saveProgressReport(Request $request) {
        $request->validate([
            'type' => 'required|string|in:ResearchLine,Individual,RoleUser',
            'related_id' => 'required|integer',
            'goals' => 'required|array',
            'goals.*.progressReport' => 'integer|required',
            'goals.*.goal' => 'numeric|nullable'
        ]);
    
        // Convertir los objetivos en formato {año: meta}
        $formattedGoals = [];
        foreach ($request->goals as $report) {
            $formattedGoals[$report['progressReport']] = $report['goal'];
        }
    
        // Determinar el campo correcto según el tipo
        $query = ProgressReportGoals::query();
    
        if ($request->type === 'ResearchLine') {
            $query->where('research_line_id', $request->related_id);
        } elseif ($request->type === 'Individual') {
            $query->where('user_id', $request->related_id);
        } elseif ($request->type === 'RoleUser') {
            $query->where('researcher_type_id', $request->related_id);
        }
    
        // Buscar si ya existe un registro
        $progressReport = $query->first();
    
        if ($progressReport) {
            // Si existe, actualizar solo los goals
            $progressReport->update(['goals' => json_encode($formattedGoals)]);
        } else {
            // Si no existe, crearlo
            $progressReport = ProgressReportGoals::create([
                'research_line_id' => $request->type === 'ResearchLine' ? $request->related_id : null,
                'user_id' => $request->type === 'Individual' ? $request->related_id : null,
                'researcher_type_id' => $request->type === 'RoleUser' ? $request->related_id : null,
                'goals' => json_encode($formattedGoals),
            ]);
        }
    
        return response()->json([
            'message' => 'Progress report saved successfully',
            'data' => $progressReport
        ], 200);
    }

    public function getProgressReport($id, Request $request) {
        $type = $request->query('type');
    
        if ($type === 'ResearchLine' && is_string($id)) {
            $researchLine = researchLines::where('id', $id)->first();
            if (!$researchLine) {
                return response()->json([
                    'message' => 'Research line not found',
                    'data' => null
                ], 404);
            }
            $id = $researchLine->id;
        }
    
        // Buscar por línea, usuario o tipo de investigador
        $progressReport = ProgressReportGoals::where(
            $type === 'ResearchLine' ? 'research_line_id' :
            ($type === 'RoleUser' ? 'researcher_type_id' : 'user_id'), 
            $id
        )->first();
    
        return response()->json([
            'message' => 'Progress report retrieved successfully',
            'data' => $progressReport
        ], 200);
    }
}
