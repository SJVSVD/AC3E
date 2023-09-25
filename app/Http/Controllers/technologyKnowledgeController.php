<?php

namespace App\Http\Controllers;
use App\Models\technologyKnowledge;
use App\Models\User;
use Illuminate\Http\Request;

class technologyKnowledgeController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $technologyKnowledge = technologyKnowledge::create($input);
        return response()->json("Registro Creado!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $technologyKnowledge = technologyKnowledge::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $technologyKnowledge = technologyKnowledge::with('usuario')->get();
        }
        return $technologyKnowledge;
    }


    public function update(Request $request, $id)
    {
        $technologyKnowledge = technologyKnowledge::find($id);
        $input = $request->all();
        $technologyKnowledge->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        technologyKnowledge::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
