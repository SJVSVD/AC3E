<?php

namespace App\Http\Controllers;
use App\Models\SYS_areas as Areas;
use Illuminate\Http\Request;

class areasController extends Controller
{
    public function index()
    {
        return Areas::with("departamento")->get();
    }

    public function store(Request $request)
    {
        $campos = [
            'idDepartamento' => 'required',
            'area' => 'required',
            'idDepArea' => 'unique:SYS_areas,idDepArea',
        ];
        $mensaje = [
            'idDepartamento.required' => 'El campo "Departamento" es obligatorio.',
            'area.required' => 'El campo "Área" es obligatorio.',
            'idDepArea.unique' => 'El Área ya existe en este Departamento.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $areas = Areas::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['area'=> 'required']);
        $areas = Areas::find($id);
        $areas->idDepartamento = $request->input('idDepartamento');
        $areas->area = $request->input('area');
        $areas->idDepArea = strval($request->input(('idDepartamento'))).$request->input('area');
        $areas->save();

        return response()->json("Area Editada");
    }

    public function destroy($id)
    {
        Areas::find($id)->delete();
        return response()->json("Departamento Eliminado");
    }
}
