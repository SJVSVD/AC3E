<?php

namespace App\Http\Controllers;
use App\Models\SYS_afp as AFP;
use Illuminate\Http\Request;

class afpController extends Controller
{
    public function index()
    {
        return AFP::all();
    }

    public function store(Request $request)
    {
        $campos = [
            'nombre' => 'required|unique:SYS_afp,nombre',
        ];
        $mensaje = [
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'nombre.unique' => 'Esta AFP ya existe.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $AFP = AFP::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['nombre'=> 'required']);
        $afp = AFP::find($id);
        $afp->nombre = $request->input('nombre');
        $afp->save();

        return response()->json("AFP Editada");
    }

    public function destroy($id)
    {
        AFP::find($id)->delete();
        return response()->json("AFP Eliminada");
    }
}