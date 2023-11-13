<?php

namespace App\Http\Controllers;
use App\Models\nonIsiPublication;
use App\Models\User;
use Illuminate\Http\Request;

class nonIsiPublicationsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('nonIsiPublication','public');
        }
        $nonIsiPublication = nonIsiPublication::create($input);
        return response()->json("Publicación Creada!");
    }

    public function nonIsiDownload($id){
        $nonIsi = nonIsiPublication::find($id);
        $pathtoFile = public_path().'/defaults/'.$nonIsi['file'];
        return response()->download($pathtoFile);
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $nonIsi = nonIsiPublication::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('nonIsiPublication','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $nonIsi = nonIsiPublication::find($request['id'])->update($input);
        return response()->json($nonIsi);
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $nonIsiPublications = nonIsiPublication::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $nonIsiPublications = nonIsiPublication::with('usuario')->get();
        }
        return $nonIsiPublications;
    }

    public function update(Request $request, $id)
    {
        $nonIsiPublication = nonIsiPublication::find($id);

        $input = $request->all();
        $nonIsiPublication->update($input);
        return response()->json("Publicación Editada");
    }

    public function destroy($id)
    {
        nonIsiPublication::find($id)->delete();
        return response()->json("Publicación Eliminado");
    }
}
