<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\exportConsolidado;
use App\Exports\exportIndividual;
use Illuminate\Http\Request;

class exportsController extends Controller
{
    public function exportConsolidado(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new exportConsolidado($idUsuarioDescarga),'Consolidado.xlsx');
    }

    public function exportIndividual(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        return Excel::download(new exportIndividual($idUsuarioDescarga),'PlanillaIndividual.xlsx');
    }
}
