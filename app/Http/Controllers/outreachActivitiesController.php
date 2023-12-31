<?php

namespace App\Http\Controllers;
use App\Models\outreachActivities;
use App\Models\User;
use Illuminate\Http\Request;

class outreachActivitiesController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $outreachActivities = outreachActivities::create($input);
        return response()->json("Registro Creado!");
    }

    public function verifyOutreach(Request $request)
    {
        $query = outreachActivities::where('activityName', $request['activityName'])
            ->where('date', $request['date'])
            ->where('country', $request['country'])
            ->whereNotNull('activityName')
            ->whereNotNull('date')
            ->whereNotNull('country');
    
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
        $outreachActivities = outreachActivities::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $outreachActivities = outreachActivities::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $outreachActivities = outreachActivities::with('usuario')->get();
        }
        return $outreachActivities;
    }


    public function update(Request $request, $id)
    {
        $outreachActivities = outreachActivities::find($id);
        $input = $request->all();
        $outreachActivities->update($input);
        return response()->json($input);
    }


    public function destroy($id)
    {
        outreachActivities::find($id)->delete();
        return response()->json("Registro Eliminado");
    }
}
