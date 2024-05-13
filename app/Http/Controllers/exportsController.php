<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\exportConsolidado;
use App\Exports\exportIndividual;
use App\Exports\ExportIndicators;
use Illuminate\Http\Request;
use App\Models\fundingSources;
use App\Models\technologyKnowledge;
use App\Models\publicPrivate;
use App\Models\patents;
use App\Models\outreachActivities;
use App\Models\postDoc;
use App\Models\thesisStudent;
use App\Models\scCollaborations;
use App\Models\participationScEvents;
use App\Models\organizationsScEvents;
use App\Models\awards;
use App\Models\books;
use App\Models\nonIsiPublication;
use App\Models\isiPublication;

class exportsController extends Controller
{
    //Funcion para exportar el consolidado general por excel
    public function exportConsolidado(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new exportConsolidado($idUsuarioDescarga),'Consolidado.xlsx');
    }
    //Funcion para exportar la planilla individual por excel
    public function exportIndividual(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new exportIndividual($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }

    public function exportStatistics(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new ExportIndicators($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }
}
