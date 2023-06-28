<?php

namespace App\Http\Controllers;
use App\Models\SYS_comunas as Comunas;
use Illuminate\Http\Request;

class comunasController extends Controller
{
    public function index()
    {
        return Comunas::with("region")->get();
    }

    public function store(Request $request)
    {
        $campos = [
            'comuna' => 'required',
            'idRegion' => 'required',
            'idRegionComuna' => 'unique:SYS_comunas,idRegionComuna',
        ];
        $mensaje = [
            'comuna.required' => 'El campo "Comuna" es obligatorio.',
            'idRegion.required' => 'El campo "Región" es obligatorio.',
            'idRegionComuna.unique' => 'La Comuna ya existe en esta Region.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $comunas = Comunas::create($input);
        return response()->json("Comuna Creada");
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['comuna'=> 'required'],['idRegion'=> 'required']);

        $comuna = Comunas::find($id);

        $comuna->comuna = $request->input('comuna');
        $comuna->idRegion = $request->input('idRegion');
        $comuna->idRegionComuna = strval($request->input(('idRegion'))).$request->input('comuna');
        $comuna->save();

        return response()->json("Comuna Editada");
    }

    public function destroy($id)
    {
        Comunas::find($id)->delete();
        return response()->json("Comuna Eliminada");
    }
}