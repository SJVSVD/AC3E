<?php

namespace App\Http\Controllers;
use App\Models\organizationsScEvents;
use App\Models\User;
use Illuminate\Http\Request;

class organizationsScEventsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('organizationsScEvents','public');
        }
        $organizationsScEvents = organizationsScEvents::create($input);
        return response()->json("Organizacion Creada!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $organizationsScEvents = organizationsScEvents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $organizationsScEvents = organizationsScEvents::with('usuario')->get();
        }
        return $organizationsScEvents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $organization = organizationsScEvents::where('id', $input['id'])->first();
        
        $input['file'] = $request->file('file')->store('organizationsScEvents','public');
        $organization->file = $input['file'];
        $organization->save();
        return response()->json($organization);
    }

    public function update(Request $request, $id)
    {
        $organizationsScEvents = organizationsScEvents::find($id);
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('organizationsScEvents','public');
        }
        $organizationsScEvents->update($input);
        return response()->json($input);
    }

    public function organizationDownload($id){
        $organization = organizationsScEvents::find($id);
        $pathtoFile = public_path().'/defaults/'.$organization['file'];
        return response()->download($pathtoFile);
    }

    public function destroy($id)
    {
        organizationsScEvents::find($id)->delete();
        return response()->json("Organizacion Eliminado");
    }
}
