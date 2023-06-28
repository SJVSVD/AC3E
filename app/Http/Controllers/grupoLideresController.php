<?php

namespace App\Http\Controllers;
use App\Models\SYS_grupoLideres as Lideres;
use Illuminate\Http\Request;

class grupoLideresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Lideres::with('usuario')->with('grupo')->get();
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
        $lideres = Lideres::create($input);
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
            // 'idUsuario.unique' => 'El Usuario ya es lider de un grupo.',
            'idGrupo.required' => 'El campo "Grupo" es obligatorio.',
            // 'idGrupo.unique' => 'El grupo ya posee lider.',
        ];
        $this->validate($request, $campos, $mensaje);

        $lider = Lideres::find($id);
        $lider->idUsuario = $request->input('idUsuario');
        $lider->idGrupo = $request->input('idGrupo');
        $lider->save();

        return response()->json("Lider Editado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lideres::find($id)->delete();
        return response()->json("Lider Eliminado");
    }
}
