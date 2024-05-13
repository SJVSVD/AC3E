<?php

namespace App\Http\Controllers;
use App\Models\fundingSources;
use App\Models\User;
use Illuminate\Http\Request;

class fundingSourcesController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $fundingSources = fundingSources::create($input);
        return response()->json("Registro Creado!");
    }

    // Función para detectar registros duplicados
     public function verifyFunding(Request $request)
    {
        $query = fundingSources::where('programContest', $request['programContest'])
            ->whereNotNull('programContest')
            ->where('projectTitle', $request['projectTitle'])
            ->whereNotNull('projectTitle');
    
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
        $fundingSources = fundingSources::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $fundingSources = fundingSources::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $fundingSources = fundingSources::with('usuario')->get();
        }
        return $fundingSources;
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $fundingSources = fundingSources::find($id);
        $input = $request->all();
        $fundingSources->update($input);
        return response()->json($input);
    }

    // Función para importar los registros que vienen desde excel
    public function importFunding(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $rowData) {
            $fundingSources = fundingSources::create([
                'idUsuario' => $rowData['idUsuario'],
                'status' => 'Finished',
                'typeSources' => $rowData['Type Sources'],
                'nameOfInstitution' => $rowData['Name of the Institution'],
                'programContest' => $rowData['Program / Contest'],
                'projectTitle' => $rowData['Project title'],
                'principalResearcher' => $rowData['Principal Researcher'],
                // 'startDate' => $rowData['Start'],
                // 'finishDate' => $rowData['Finish'],
                'comments' => $rowData['Comentarios'],
                // 'inCash' => $rowData['In Cash'],
                'typeOfCollaboration' => $rowData['Type of collaboration'],
                'progressReport' => $rowData['Progress Report'],
            ]);
        }
        
        return response()->json("Publicaciónes importadas");
    }

     // Función para eliminar un registro
    public function destroy($id)
    {
        fundingSources::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
