<?php

namespace App\Http\Controllers;
use App\Models\awards;
use App\Models\User;
use Illuminate\Http\Request;

class awardsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $awards = awards::create($input);
        return response()->json("Premio Creada!");
    }

    public function verifyAward(Request $request)
    {
        $query = awards::where('awardName', $request['awardName'])
            ->where('institution', $request['institution'])
            ->whereNotNull('awardName')
            ->whereNotNull('institution');
    
        if ($request->has('id')) {
            $query->where('id', '!=', $request->input('id'));
        }
    
        $existentes = $query->count();
    
        return response()->json($existentes); 
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $awards = awards::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $awards = Awards::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $awards = awards::with('usuario')->get();
        }
        return $awards;
    }

    public function update(Request $request, $id)
    {
        $awards = awards::find($id);

        $input = $request->all();
        $awards->update($input);
        return response()->json("Premio Editado");
    }

    public function destroy($id)
    {
        awards::find($id)->delete();
        return response()->json("Premio Eliminado");
    }
}
