<?php

namespace App\Http\Controllers;
use App\Models\SYS_nacionalidades as Nacionalidades;
use Illuminate\Http\Request;

class nacionalidadesController extends Controller
{
    public function index()
    {
        return Nacionalidades::with("pais")->get();
    }

    public function store(Request $request)
    {
        $campos = [
            'nacionalidad' => 'required|unique:SYS_nacionalidades,nacionalidad',
            'idPais' => 'required',
        ];
        $mensaje = [
            'nacionalidad.required' => 'El campo "Nacionalidad" es obligatorio.',
            'nacionalidad.unique' => 'Esta nacionalidad ya existe.',
            'idPais.required' => 'El campo "País" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $nacionalidad = Nacionalidades::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['nacionalidad'=> 'required'],['idPais'=> 'required']);
        $nacionalidad = Nacionalidades::find($id);
        $nacionalidad->nacionalidad = $request->input('nacionalidad');
        $nacionalidad->idPais = $request->input('idPais');
        $nacionalidad->save();
        return response()->json("Nacionalidad Editado");
    }

    public function destroy($id)
    {
        Nacionalidades::find($id)->delete();
        return response()->json("Nacionalidad Eliminada");
    }
}