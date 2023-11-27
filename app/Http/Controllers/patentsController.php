<?php

namespace App\Http\Controllers;
use App\Models\patents;
use App\Models\User;
use Illuminate\Http\Request;

class patentsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $patents = patents::create($input);
        return response()->json("Registro Creado!");
    }

    public function verifyPatent(Request $request)
    {
        $existentes = patents::where('registrationNumber', $request['registrationNumber'])->whereNotNull('registrationNumber')->count();
        return response()->json($existentes); 
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $patents = patents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $patents = patents::with('usuario')->get();
        }
        return $patents;
    }


    public function update(Request $request, $id)
    {
        $patents = patents::find($id);
        $input = $request->all();
        $patents->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        patents::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
