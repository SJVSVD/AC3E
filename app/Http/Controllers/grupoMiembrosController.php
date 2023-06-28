<?php

namespace App\Http\Controllers;
use App\Models\SYS_grupoMiembros as Miembros;
use Illuminate\Http\Request;

class grupoMiembrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Miembros::with('usuario')->with('grupo')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos = [
            'idUsuario' => 'required',
            'idGrupo' => 'required',
        ];
        $mensaje = [
            'idUsuario.required' => 'El campo "Usuario" es obligatorio.',
            'idGrupo.required' => 'El campo "Grupo" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $miembros = Miembros::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campos = [
            'idUsuario' => 'required',
            'idGrupo' => 'required',
        ];
        $mensaje = [
            'idUsuario.required' => 'El campo "Usuario" es obligatorio.',
            'idGrupo.required' => 'El campo "Grupo" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);

        $miembro = Miembros::find($id);
        $miembro->idUsuario = $request->input('idUsuario');
        $miembro->idGrupo = $request->input('idGrupo');
        $miembro->save();

        return response()->json("Miembro Editado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Miembros::find($id)->delete();
        return response()->json("Miembro Eliminado");
    }
}
