<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\publicPrivate;
use Illuminate\Http\Request;

class publicPrivateController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $publicPrivate = publicPrivate::create($input);
        return response()->json("Registro Creado!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $publicPrivate = publicPrivate::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $publicPrivate = publicPrivate::with('usuario')->get();
        }
        return $publicPrivate;
    }


    public function update(Request $request, $id)
    {
        $publicPrivate = publicPrivate::find($id);
        $input = $request->all();
        $publicPrivate->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        publicPrivate::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
