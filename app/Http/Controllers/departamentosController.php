<?php

namespace App\Http\Controllers;
use App\Models\SYS_departamentos as Departamentos;
use Illuminate\Http\Request;

class departamentosController extends Controller
{
    public function index()
    {
        return Departamentos::all();
    }

    public function store(Request $request)
    {
        $campos = [
            'departamento' => 'required|unique:SYS_departamentos,departamento',
        ];
        $mensaje = [
            'departamento.required' => 'El campo "Departamento" es obligatorio.',
            'departamento.unique' => 'Este departamento ya existe.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $departamentos = Departamentos::create($input);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['departamento'=> 'required']);
        $depto = Departamentos::find($id);
        $depto->departamento = $request->input('departamento');
        $depto->save();
        return response()->json("Departamento Editado");
    }

    public function destroy($id)
    {
        Departamentos::find($id)->delete();
        return response()->json("Departamento Eliminado");
    }
}