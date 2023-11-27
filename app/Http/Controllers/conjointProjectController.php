<?php

namespace App\Http\Controllers;
use App\Models\scCollaborations;
use App\Models\User;
use Illuminate\Http\Request;

class conjointProjectController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $scCollaborations = scCollaborations::create($input);
        return response()->json("Colaboracion Creada!");
    }

    public function verifyConjoint(Request $request)
    {
        $existentes = scCollaborations::where('moduleType', 1)->where('nameOfAC3EMember', $request['nameOfAC3EMember'])->where('nameOfExternalResearcher', $request['nameOfExternalResearcher'])->where('beginningDate', $request['beginningDate'])->whereNotNull('beginningDate')->whereNotNull('nameOfAC3EMember')->whereNotNull('nameOfExternalResearcher')->count();
        return response()->json($existentes); 
    }

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
        if($administrador == true){
            $scCollaborations = scCollaborations::with('usuario')->where('moduleType',1)->get();
        }
        return $scCollaborations;
    }


    public function update(Request $request, $id)
    {
        $scCollaborations = scCollaborations::find($id);
        $input = $request->all();
        $scCollaborations->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        scCollaborations::find($id)->delete();
        return response()->json("Colaboracion Eliminado");
    }
}
