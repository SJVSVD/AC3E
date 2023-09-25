<?php

namespace App\Http\Controllers;
use App\Models\scCollaborations;
use App\Models\User;
use Illuminate\Http\Request;

class scCollaborationsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $scCollaborations = scCollaborations::create($input);
        return response()->json("Colaboracion Creada!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $scCollaborations = scCollaborations::where('idUsuario', $userID)->where('moduleType',0)->with('usuario')->get();
        
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Administrador'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == true){
            $scCollaborations = scCollaborations::with('usuario')->where('moduleType',0)->get();
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
