<?php

namespace App\Http\Controllers;
use App\Models\SYS_manuales as Manuales;
use Illuminate\Http\Request;
use App\Models\User;

class manualesController extends Controller
{
    public function index()
    {
        return Manuales::all();
    }

    // Mandar lista de fofis
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $jefatura = false;
        $manuales = Manuales::all();
        $user = User::where('id', $userID)->with('roles')->get();
        $roles = $user[0]['roles'];
        $manualesUsuario = [];
        // Mantener aquellos que cumplen con los roles del usuario:
        foreach ($roles as $rol){
            foreach ($manuales as $manual){
                if ($rol['name'] == $manual['modulo']){
                    array_push($manualesUsuario, $manual);
                }
            }
        }
        return $manualesUsuario;
    }

    public function store(Request $request)
    {
        $campos = [
            'titulo' => 'required',
            'modulo' => 'required',
        ];
        $mensaje = [
            'titulo.required' => 'El campo "Título" es obligatorio.',
            'modulo.required' => 'El campo "Modulo" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        if($request->hasFile('archivo')){
            $input['archivo'] = $request->file('archivo')->store('manuales','public');
        }
        Manuales::create($input);
    }
    
    public function manualDownload($id){
        $manual = Manuales::find($id);
        $pathtoFile = public_path().'/defaults/'.$manual['archivo'];
        return response()->download($pathtoFile);
    }

    public function destroy($id)
    {
        Manuales::find($id)->delete();
        return response()->json("Manual Eliminada");
    }
}
