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

    public function verifyOrganization(Request $request)
    {
        $query = organizationsScEvents::where('eventName', $request['eventName'])
            ->where('startDate', $request['startDate'])
            ->where('idUsuario', $request['idUsuario'])
            ->whereNotNull('eventName')
            ->whereNotNull('startDate');
    
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
        $organizationsScEvents = organizationsScEvents::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $organizationsScEvents = organizationsScEvents::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $organizationsScEvents = organizationsScEvents::with('usuario')->get();
        }
        return $organizationsScEvents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $organization = organizationsScEvents::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('organizationsScEvents','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $organization = organizationsScEvents::find($request['id'])->update($input);
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
