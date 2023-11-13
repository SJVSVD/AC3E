<?php

namespace App\Http\Controllers;
use App\Models\participationScEvents;
use App\Models\User;
use Illuminate\Http\Request;

class participationsScEventsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('participationScEvents','public');
        }
        $participationScEvents = participationScEvents::create($input);
        return response()->json("Participacion Creada!");
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $participationScEvents = participationScEvents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $participationScEvents = participationScEvents::with('usuario')->get();
        }
        return $participationScEvents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $participation = participationScEvents::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('participationScEvents','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $participation = participationScEvents::find($request['id'])->update($input);
        return response()->json($participation);
    }

    public function update(Request $request, $id)
    {
        $participationScEvents = participationScEvents::find($id);
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('participationScEvents','public');
        }
        $participationScEvents->update($input);
        return response()->json($input);
    }

    public function participationDownload($id){
        $organization = participationScEvents::find($id);
        $pathtoFile = public_path().'/defaults/'.$organization['file'];
        return response()->download($pathtoFile);
    }

    public function destroy($id)
    {
        participationScEvents::find($id)->delete();
        return response()->json("Participacion Eliminado");
    }
}
