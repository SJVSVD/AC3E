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
use App\Models\User;

class exportsController extends Controller
{
    //Funcion para exportar el consolidado general por excel
    public function exportConsolidado(Request $request)
    {
        ini_set('memory_limit', '512M');
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new exportConsolidado($idUsuarioDescarga),'Consolidado.xlsx')->queue();
    }


// Función para exportar la planilla individual por Excel
public function exportIndividual(Request $request)
{
    ini_set('memory_limit', '512M');
        // Función para normalizar strings (nombres)
    function normalizeString($string) {
        // Eliminar acentos y convertir a minúsculas
        $string = strtolower($string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        // Eliminar caracteres especiales
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);
        // Eliminar espacios adicionales
        $string = trim($string);

        return $string;
    }

    $data = $request->all();
    $idUsuarioDescarga = $data['userID'];

    // Normalizar el nombre del usuario
    $userName = normalizeString(User::findOrFail($idUsuarioDescarga)->name);

    // Definir los modelos que pueden tener el campo researcherInvolved
    $modelsWithResearcherInvolved = [
        IsiPublication::class,
        NonIsiPublication::class,
        Books::class,
        ThesisStudent::class,
        ScCollaborations::class,
        ParticipationScEvents::class,
        OrganizationsScEvents::class,
        OutreachActivities::class,
        PostDoc::class,
        Patents::class,
        PublicPrivate::class,
        TechnologyKnowledge::class,
        FundingSources::class
    ];

    // Verificar si el usuario tiene publicaciones asociadas con idUsuario
    $hasPublicationsByID = FundingSources::where('idUsuario', $idUsuarioDescarga)->exists() ||
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

    // Verificar si el usuario está involucrado por nombre normalizado en el campo researcherInvolved
    $hasPublicationsByResearcherInvolved = false;

    foreach ($modelsWithResearcherInvolved as $model) {
        $publications = $model::all()->filter(function($publication) use ($userName) {
            // Verificar si la publicación tiene el campo researcherInvolved
            if (isset($publication->researcherInvolved)) {
                $normalizedResearcher = normalizeString($publication->researcherInvolved);
                // Verificar si el nombre normalizado del usuario está en el campo
                return strpos($normalizedResearcher, $userName) !== false;
            }
            return false;
        });

        // Si encontramos alguna publicación en el campo researcherInvolved, cambiamos a true
        if (!$publications->isEmpty()) {
            $hasPublicationsByResearcherInvolved = true;
            break;
        }
    }

    // Si no hay publicaciones por ID o por el campo researcherInvolved, retornar error
    if (!$hasPublicationsByID && !$hasPublicationsByResearcherInvolved) {
        return response()->json('El usuario no tiene publicaciones asociadas.', 400);
    }

    // Retornar el archivo de Excel con la información exportada
    return Excel::download(new exportIndividual($idUsuarioDescarga), 'PlanillaIndividual.xlsx')->queue();
}


    public function exportStatistics(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new ExportIndicators($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }
}
