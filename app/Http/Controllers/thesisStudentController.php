<?php

namespace App\Http\Controllers;
use App\Models\thesisStudent;
use App\Models\User;
use Illuminate\Http\Request;

class thesisStudentController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $input['file'] = $request->file('file')->store('thesisExtracts','public');
        }
        $thesisStudent = thesisStudent::create($input);
        return response()->json("Thesis Creada!");
    }

    public function verifyThesis(Request $request)
    {
        $query = thesisStudent::where('identification', $request['identification'])
            ->where('degreeDenomination', $request['degreeDenomination'])
            ->whereNotNull('identification')
            ->whereNotNull('degreeDenomination');
    
        // Si el request trae el campo 'id', agregamos una condición para excluir ese ID de la búsqueda
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
        $thesisStudents = thesisStudent::where('idUsuario', $userID)->with('usuario')->get();
        
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
            $thesisStudents = thesisStudent::where(function($query) use ($userName, $userID) {
                $query->where('researcherInvolved', 'LIKE', "%{$userName}.%")
                      ->orWhere('idUsuario', $userID);
            })->with('usuario')->get();
        }else{
            $thesisStudents = thesisStudent::with('usuario')->get();
        }
        return $thesisStudents;
    }

    public function addFile(Request $request){
        $input = $request->all();
        
        $thesis = thesisStudent::where('id', $input['id'])->first();
        if(gettype($input['file']) == 'object'){
            if($request->hasFile('file')){
                $input['file'] = $request->file('file')->store('thesisExtracts','public');
            }
        }else if($input['file'] == 'null'){
            unset($input['file']);
        }
        $thesis = thesisStudent::find($request['id'])->update($input);
        return response()->json($thesis);
    }

    public function thesisDownload($id){
        $thesis = thesisStudent::find($id);
        $pathtoFile = public_path().'/defaults/'.$thesis['file'];
        return response()->download($pathtoFile);
    }

    public function update(Request $request, $id)
    {
        $thesis = thesisStudent::find($id);

        $input = $request->all();
        $thesis->update($input);
        return response()->json("Thesis Editada");
    }

    public function destroy($id)
    {
        thesisStudent::find($id)->delete();
        return response()->json("Thesis Eliminado");
    }
}
