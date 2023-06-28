<?php

namespace App\Http\Controllers;
use App\Models\SYS_estadosCiviles as EstadosCiviles;
use Illuminate\Http\Request;

class estadosCivilesController extends Controller
{
    public function index()
    {
        return EstadosCiviles::all();
    }

    public function store(Request $request)
    {
        $campos = [
            'nombre' => 'required|unique:SYS_estadosCiviles,nombre',
        ];
        $mensaje = [
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'nombre.unique' => 'Este estado civil ya existe.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $estadoCivil = EstadosCiviles::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['nombre'=> 'required']);

        $Estado = EstadosCiviles::find($id);

        $Estado->nombre = $request->input('nombre');
        $Estado->save();

        return response()->json("Estado Editado");
    }

    public function destroy($id)
    {
        EstadosCiviles::find($id)->delete();
        return response()->json("Estado Civil Eliminada");
    }
}