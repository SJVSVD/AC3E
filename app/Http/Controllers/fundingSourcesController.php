<?php

namespace App\Http\Controllers;
use App\Models\fundingSources;
use App\Models\User;
use Illuminate\Http\Request;

class fundingSourcesController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $fundingSources = fundingSources::create($input);
        return response()->json("Registro Creado!");
    }

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
                if ($rol['name'] == 'Administrador'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == true){
            $fundingSources = fundingSources::with('usuario')->get();
        }
        return $fundingSources;
    }


    public function update(Request $request, $id)
    {
        $fundingSources = fundingSources::find($id);
        $input = $request->all();
        $fundingSources->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        fundingSources::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
