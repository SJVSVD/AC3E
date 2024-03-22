<?php

namespace App\Http\Controllers;
use App\Models\patents;
use App\Models\User;
use Illuminate\Http\Request;

class patentsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $patents = patents::create($input);
        return response()->json("Registro Creado!");
    }

    public function verifyPatent(Request $request)
    {
        $query = patents::where('registrationNumber', $request['registrationNumber'])
            ->whereNotNull('registrationNumber');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $patents = patents::where('idUsuario', $userID)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Administrator'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == false){
            $userName = User::findOrFail($userID)->name;
            $patents = patents::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $patents = patents::with('usuario')->get();
        }
        return $patents;
    }


    public function update(Request $request, $id)
    {
        $patents = patents::find($id);
        $input = $request->all();
        $patents->update($input);
        return response()->json($input);
    }

    public function importPatent(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Obtén el valor de 'attendantsAmount' del $rowData
            $grantDate = $rowData['Grant Date'];

            // Verifica si el valor es un entero
            if (!is_numeric($grantDate)) {
                // Si no es un número, establece 'grantDate' en 0
                $grantDate = null;
            }

            $patents = patents::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'ipType' => $rowData['IP Type'],
                'authors' => $rowData['Authors'],
                'institutionOwner' => $rowData['Institution Owner(s)'],
                'countryOfRegistration' => $rowData['Country of Registration'],
                'applicationDate' => $rowData['Application Date'],
                'grantDate' => $grantDate,
                'applicationStatus' => $rowData['Application Status'],
                'registrationNumber' => $rowData['Registration Number'],
                'nameOfPatent' => $rowData['Name of Patent Applications'],
                'researcherInvolved' => $rowData['Researcher Involved'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


    public function destroy($id)
    {
        patents::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
