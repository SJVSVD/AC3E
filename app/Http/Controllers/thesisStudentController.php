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
        if($request->hasFile('thesisExtract')){
            $input['thesisExtract'] = $request->file('thesisExtract')->store('thesisExtracts','public');
        }
        $thesisStudent = thesisStudent::create($input);
        return response()->json("Thesis Creada!");
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
                if ($rol['name'] == 'Administrador'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
            }
        }
        if($administrador == true){
            $thesisStudents = thesisStudent::with('usuario')->get();
        }
        return $thesisStudents;
    }

    public function thesisDownload($id){
        $thesis = thesisStudent::find($id);
        $pathtoFile = public_path().'/defaults/'.$thesis['thesisExtract'];
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