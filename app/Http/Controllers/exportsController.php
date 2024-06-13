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

        // Check if the user has any publications in any of the models
        $hasPublications = FundingSources::where('idUsuario', $idUsuarioDescarga)->exists() ||
        TechnologyKnowledge::where('idUsuario', $idUsuarioDescarga)->exists() ||
        PublicPrivate::where('idUsuario', $idUsuarioDescarga)->exists() ||
        Patents::where('idUsuario', $idUsuarioDescarga)->exists() ||
        OutreachActivities::where('idUsuario', $idUsuarioDescarga)->exists() ||
        PostDoc::where('idUsuario', $idUsuarioDescarga)->exists() ||
        ThesisStudent::where('idUsuario', $idUsuarioDescarga)->exists() ||
        ScCollaborations::where('idUsuario', $idUsuarioDescarga)->exists() ||
        ParticipationScEvents::where('idUsuario', $idUsuarioDescarga)->exists() ||
        OrganizationsScEvents::where('idUsuario', $idUsuarioDescarga)->exists() ||
        Awards::where('idUsuario', $idUsuarioDescarga)->exists() ||
        Books::where('centerResearcher', $idUsuarioDescarga)->exists() ||
        NonIsiPublication::where('idUsuario', $idUsuarioDescarga)->exists() ||
        IsiPublication::where('idUsuario', $idUsuarioDescarga)->exists();

        // If the user does not have any publications, return an error response
        if (!$hasPublications) {
            return response()->json('El usuario no tiene publicaciones asociadas.', 400);
        }

        return Excel::download(new exportIndividual($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }

    public function exportStatistics(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new ExportIndicators($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }
}
