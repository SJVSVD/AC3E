<?php

namespace App\Http\Controllers;
use App\Models\ETC_comisiones as Comisiones;
use Illuminate\Http\Request;

class comisionesController extends Controller
{
    public function index(){
        return Comisiones::with('usuario')->get();
    }
    
    public function store(Request $request)
    {
        $campos = [
            'estado' => 'required',
            'rut' => 'required',
            'nombre' => 'required',
            'correo' => 'required',
        ];
        $mensaje = [
            'estado.required' => 'El campo "Estado" es obligatorio.',
            'rut.required' => 'El campo "Rut" es obligatorio.',
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'correo.required' => 'El campo "Correo" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $comisiones = Comisiones::create($input);
    }

    public function update(Request $request, $id)
    {
        $campos = [
            'estado' => 'required',
            'rut' => 'required',
            'nombre' => 'required',
            'correo' => 'required',
        ];
        $mensaje = [
            'estado.required' => 'El campo "Estado" es obligatorio.',
            'rut.required' => 'El campo "Rut" es obligatorio.',
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'correo.required' => 'El campo "Correo" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);

        $comision = Comisiones::find($id);

        $comision->estado = $request->input('estado');
        $comision->rut = $request->input('rut');
        $comision->nombre = $request->input('nombre');
        $comision->correo = $request->input('correo');
        $comision->telefono = $request->input('telefono');
        $comision->idUsuario = $request->input('idUsuario');
        $comision->save();

        return response()->json("Comisión Editada");
    }

    public function destroy($id)
    {
        Comisiones::find($id)->delete();
        return response()->json("Comisión Eliminada");
    }
}
