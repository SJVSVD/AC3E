<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\postDoc;
use Illuminate\Http\Request;

class postDocController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $postDoc = postDoc::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
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

    // Función para mostrar registros y verificar si es administrador  o no lo es
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


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $postDoc = postDoc::find($id);
        $input = $request->all();
        $postDoc->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importPostdoc(Request $request)
    {
        // Arreglo de mapeo para los recursos proporcionados
        $resourcesMapping = [
            '1' => 'Equipment',
            '2' => 'Information',
            '3' => 'Infrastructure',
            '4' => 'Other',
        ];
        
        $data = $request->input('data');
        foreach ($data as $rowData) {
            // Convertir el valor del género a Male o Female
            $gender = $rowData['Gender (M/F)'] === 'M' ? 'Male' : 'Female';
            
            // Convertir el valor de RUN / Passport a minúsculas
            $identification = strtolower($rowData['Identification']);
            
            // Mapear el valor de los recursos proporcionados
            $resourcesProvided = '';
            if (isset($rowData['Resources provided by the Center'])) {
                $resources = explode(',', $rowData['Resources provided by the Center']);
                foreach ($resources as $resource) {
                    $resource = trim($resource);
                    if (isset($resourcesMapping[$resource])) {
                        $resourcesProvided .= $resourcesMapping[$resource] . ', ';
                    }
                }
                $resourcesProvided = rtrim($resourcesProvided, ', '); // Eliminar la última coma y espacio
            }
            
            $postDoc = postDoc::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => $rowData['Status'],
                'nameOfPostdoc' => $rowData['Name of Postdoctoral Fellows'],
                'identification' => $identification,
                'runOrPassport' => $rowData['RUN / Passport'], // Asignar el valor convertido a minúsculas
                'gender' => $gender, // Asignar el valor convertido
                'researchTopic' => $rowData['Research Topic'],
                'supervisorName' => $rowData["Tutor's name"],
                'resourcesProvided' => $resourcesProvided, // Asignar los recursos proporcionados
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
        
        return response()->json("Publicaciones importadas");
    }
    

     // Función para eliminar un registro
    public function destroy($id)
    {
        postDoc::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
