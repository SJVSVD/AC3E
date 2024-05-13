<?php

namespace App\Http\Controllers;
use App\Models\scCollaborations;
use App\Models\User;
use Illuminate\Http\Request;

class conjointProjectController extends Controller
{
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $input = $request->all();
        $scCollaborations = scCollaborations::create($input);
        return response()->json("Colaboracion Creada!");
    }

    // Función para detectar registros duplicados
     public function verifyConjoint(Request $request)
    {
        $query = scCollaborations::where('moduleType', 1)
            ->where('nameOfExternalResearcher', $request['nameOfExternalResearcher'])
            ->where('beginningDate', $request['beginningDate'])
            ->whereNotNull('beginningDate')
            ->whereNotNull('nameOfExternalResearcher');
    
        // Si el request trae el campo 'id', agregamos una condición para excluir ese ID de la búsqueda
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
        $scCollaborations = scCollaborations::where('idUsuario', $userID)->where('moduleType',1)->with('usuario')->get();
        
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
            $scCollaborations = scCollaborations::where(function($query) use ($userName, $userID) {
                $query->where('moduleType',1)->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $scCollaborations = scCollaborations::where('moduleType',1)->with('usuario')->get();
        }
        return $scCollaborations;
    }


     // Función para editar un registro
    public function update(Request $request, $id)
    {
        $scCollaborations = scCollaborations::find($id);
        $input = $request->all();
        $scCollaborations->update($input);
        return response()->json($input);
    }


     // Función para eliminar un registro
    public function destroy($id)
    {
        scCollaborations::find($id)->delete();
        return response()->json("Colaboracion Eliminado");
    }
}
