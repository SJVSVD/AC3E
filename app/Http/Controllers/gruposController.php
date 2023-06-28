<?php

namespace App\Http\Controllers;
use App\Models\SYS_grupos as Grupos;
use Illuminate\Http\Request;

class gruposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Grupos::with('grupoLider')->with('grupoMiembros')->get();
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
            'nombre' => 'required|unique:SYS_grupos,nombre',
        ];
        $mensaje = [
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'nombre.unique' => 'Este grupo ya existe.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $grupos = Grupos::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grupo = Grupos::with('usuario')->with('grupoLider')->with('grupoMiembros')->find($id);
        return $grupo;
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
            'nombre' => 'required|unique:SYS_grupos,nombre',
        ];
        $mensaje = [
            'nombre.required' => 'El campo "Nombre" es obligatorio.',
            'nombre.unique' => 'Este grupo ya existe.',
        ];
        $this->validate($request, $campos, $mensaje);

        $grupo = Grupos::find($id);

        $grupo->nombre = $request->input('nombre');
        $grupo->save();

        return response()->json("Grupo Editado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Grupos::find($id)->delete();
        return response()->json("Grupo Eliminado");
    }
}
