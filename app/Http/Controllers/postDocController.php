<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\postDoc;
use Illuminate\Http\Request;

class postDocController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $postDoc = postDoc::create($input);
        return response()->json("Registro Creado!");
    }

    public function verifyPostdoc(Request $request)
    {
        $query = postDoc::where('identification', $request['identification'])
            ->whereNotNull('identification');
    
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
        $postDoc = postDoc::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $postDoc = postDoc::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $postDoc = postDoc::with('usuario')->get();
        }
        return $postDoc;
    }


    public function update(Request $request, $id)
    {
        $postDoc = postDoc::find($id);
        $input = $request->all();
        $postDoc->update($input);
        return response()->json($input);
    }

    public function importPostdoc(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            $postDoc = postDoc::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'nameOfPostdoc' => $rowData['Name of Postdoctoral Fellows'],
                'identification' => $rowData['Identification'],
                'runOrPassport' => $rowData['RUN / Passport'],
                'gender' => $rowData['Gender (M/F)'],
                'researchTopic' => $rowData['Research Topic'],
                'supervisorName' => $rowData["Tutor's name"],
                'resourcesProvided' => $rowData['Resources provided by the Center'],
                'fundingSource' => $rowData['Funding Source'],
                'startYear' => $rowData['Start Year'],
                'endingYear' => $rowData['Ending Year'],
                // 'privateSector' => $rowData[''],
                // 'academy1' => $rowData[''],
                // 'business' => $rowData[''],
                // 'ownEntrepreneurship' => $rowData[''],
                // 'publicSector' => $rowData[''],
                // 'government' => $rowData[''],
                // 'academy2' => $rowData[''],
                // 'socialOng' => $rowData[''],
                // 'inTheCenter' => $rowData[''],
                // 'noneOfTheAbove' => $rowData[''],
                'institutionName' => $rowData['Associated Institution'],
                'comments' => $rowData['Comentarios'],
                'progressReport' => $rowData['Progress Report'],
                'personalEmail' => $rowData['Personal E-mail'],

            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }


    public function destroy($id)
    {
        postDoc::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
