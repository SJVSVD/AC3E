<?php

namespace App\Http\Controllers;
use App\Models\SYS_autorizadores as Autorizadores;
use App\Models\User;
use Illuminate\Http\Request;

class autorizadoresController extends Controller
{
    public function index()
    {
        return Autorizadores::with("usuario")->get();
    }

    public function getAutorizadores()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Autorizadores::create($input);

        return response()->json('Se ha guardado todo con exito');
        
    }    

    public function update(Request $request, $id)
    {
        $Autorizador = Autorizadores::find($id);

        $input = $request->all();
        $Autorizador->update($input);
        return response()->json("Autorizador Editado");
    }

    public function destroy($id)
    {
        Autorizadores::find($id)->delete();
        return response()->json("Autorizador Eliminado");
    }
}
