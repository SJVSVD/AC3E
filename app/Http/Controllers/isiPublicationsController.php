<?php

namespace App\Http\Controllers;
use App\Models\isiPublication;
use App\Models\User;
use Illuminate\Http\Request;

class isiPublicationsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $isiPublication = isiPublication::create($input);
        return response()->json("Publicación Creada!");
    }

    // Mandar lista de fofis
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $isiPublications = isiPublication::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $isiPublications = isiPublication::with('usuario')->get();
        }
        return $isiPublications;
    }

    public function update(Request $request, $id)
    {
        $isiPublication = isiPublication::find($id);

        $input = $request->all();
        $isiPublication->update($input);
        return response()->json("Publicación Editada");
    }

    public function destroy($id)
    {
        isiPublication::find($id)->delete();
        return response()->json("Publicación Eliminado");
    }
}
