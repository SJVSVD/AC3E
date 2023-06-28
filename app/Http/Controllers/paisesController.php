<?php

namespace App\Http\Controllers;
use App\Models\SYS_paises as Paises;
use Illuminate\Http\Request;

class paisesController extends Controller
{
    public function index()
    {
        return Paises::all();
    }

    public function store(Request $request)
    {
        $campos = [
            'pais' => 'required|unique:SYS_paises,pais',
            'capital' => 'required',
        ];
        $mensaje = [
            'pais.required' => 'El campo "País" es obligatorio.',
            'pais.unique' => 'Este país ya existe.',
            'capital.required' => 'El campo "Capital" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $paises = Paises::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['pais'=> 'required'],['capital'=> 'required']);
        $pais = Paises::find($id);
        $pais->pais = $request->input('pais');
        $pais->capital = $request->input('capital');
        $pais->save();
        return response()->json("Pais Editado");
    }

    public function destroy($id)
    {
        Paises::find($id)->delete();
        return response()->json("País Eliminado");
    }
}