<?php

namespace App\Http\Controllers;
use App\Models\TIE_contadorPersonas as ContadorPersonas;
use Illuminate\Http\Request;

class contadorPersonasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ContadorPersonas::with('tienda')->get();
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
            'idTienda' => 'required',
            'fecha' => 'required',
            'tipoEntrada' => 'required',
            'h1' => 'required',
            'h2' => 'required',
            'h3' => 'required',
            'h4' => 'required',
            'h5' => 'required',
            'h6' => 'required',
            'h7' => 'required',
            'h8' => 'required',
            'h9' => 'required',
            'h10' => 'required',
            'h11' => 'required',
            'h12' => 'required',
            'h13' => 'required',
            'h14' => 'required',
            'h15' => 'required',
            'h16' => 'required',
            'h17' => 'required',
            'h18' => 'required',
            'h19' => 'required',
            'h20' => 'required',
            'h21' => 'required',
            'h22' => 'required',
            'h23' => 'required',
            'h24' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio',
            'fecha.required' => 'El campo "Fecha" es obligatorio',
            'tipoEntrada.required' => 'El campo "Tipo de Entrada" es obligatorio',
            'h1.required' => 'El campo "Hora 1" es obligatorio',
            'h2.required' => 'El campo "Hora 2" es obligatorio',
            'h3.required' => 'El campo "Hora 3" es obligatorio',
            'h4.required' => 'El campo "Hora 4" es obligatorio',
            'h5.required' => 'El campo "Hora 5" es obligatorio',
            'h6.required' => 'El campo "Hora 6" es obligatorio',
            'h7.required' => 'El campo "Hora 7" es obligatorio',
            'h8.required' => 'El campo "Hora 8" es obligatorio',
            'h9.required' => 'El campo "Hora 9" es obligatorio',
            'h10.required' => 'El campo "Hora 10" es obligatorio',
            'h11.required' => 'El campo "Hora 11" es obligatorio',
            'h12.required' => 'El campo "Hora 12" es obligatorio',
            'h13.required' => 'El campo "Hora 13" es obligatorio',
            'h14.required' => 'El campo "Hora 14" es obligatorio',
            'h15.required' => 'El campo "Hora 15" es obligatorio',
            'h16.required' => 'El campo "Hora 16" es obligatorio',
            'h17.required' => 'El campo "Hora 17" es obligatorio',
            'h18.required' => 'El campo "Hora 18" es obligatorio',
            'h19.required' => 'El campo "Hora 19" es obligatorio',
            'h20.required' => 'El campo "Hora 20" es obligatorio',
            'h21.required' => 'El campo "Hora 21" es obligatorio',
            'h22.required' => 'El campo "Hora 22" es obligatorio',
            'h23.required' => 'El campo "Hora 23" es obligatorio',
            'h24.required' => 'El campo "Hora 24" es obligatorio',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $contadorPersonas = ContadorPersonas::create($input);
        return response()->json("Contador de Personas Creado");
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
            'idTienda' => 'required',
            'fecha' => 'required',
            'tipoEntrada' => 'required',
            'h1' => 'required',
            'h2' => 'required',
            'h3' => 'required',
            'h4' => 'required',
            'h5' => 'required',
            'h6' => 'required',
            'h7' => 'required',
            'h8' => 'required',
            'h9' => 'required',
            'h10' => 'required',
            'h11' => 'required',
            'h12' => 'required',
            'h13' => 'required',
            'h14' => 'required',
            'h15' => 'required',
            'h16' => 'required',
            'h17' => 'required',
            'h18' => 'required',
            'h19' => 'required',
            'h20' => 'required',
            'h21' => 'required',
            'h22' => 'required',
            'h23' => 'required',
            'h24' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio',
            'fecha.required' => 'El campo "Fecha" es obligatorio',
            'tipoEntrada.required' => 'El campo "Tipo de Entrada" es obligatorio',
            'h1.required' => 'El campo "Hora 1" es obligatorio',
            'h2.required' => 'El campo "Hora 2" es obligatorio',
            'h3.required' => 'El campo "Hora 3" es obligatorio',
            'h4.required' => 'El campo "Hora 4" es obligatorio',
            'h5.required' => 'El campo "Hora 5" es obligatorio',
            'h6.required' => 'El campo "Hora 6" es obligatorio',
            'h7.required' => 'El campo "Hora 7" es obligatorio',
            'h8.required' => 'El campo "Hora 8" es obligatorio',
            'h9.required' => 'El campo "Hora 9" es obligatorio',
            'h10.required' => 'El campo "Hora 10" es obligatorio',
            'h11.required' => 'El campo "Hora 11" es obligatorio',
            'h12.required' => 'El campo "Hora 12" es obligatorio',
            'h13.required' => 'El campo "Hora 13" es obligatorio',
            'h14.required' => 'El campo "Hora 14" es obligatorio',
            'h15.required' => 'El campo "Hora 15" es obligatorio',
            'h16.required' => 'El campo "Hora 16" es obligatorio',
            'h17.required' => 'El campo "Hora 17" es obligatorio',
            'h18.required' => 'El campo "Hora 18" es obligatorio',
            'h19.required' => 'El campo "Hora 19" es obligatorio',
            'h20.required' => 'El campo "Hora 20" es obligatorio',
            'h21.required' => 'El campo "Hora 21" es obligatorio',
            'h22.required' => 'El campo "Hora 22" es obligatorio',
            'h23.required' => 'El campo "Hora 23" es obligatorio',
            'h24.required' => 'El campo "Hora 24" es obligatorio',
        ];
        $this->validate($request, $campos, $mensaje);
        $contadorPersonas = ContadorPersonas::find($id);
        $contadorPersonas->idTienda = $request->input('idTienda');
        $contadorPersonas->fecha = $request->input('fecha');
        $contadorPersonas->tipoEntrada = $request->input('tipoEntrada');
        $contadorPersonas->h1 = $request->input('h1');
        $contadorPersonas->h2 = $request->input('h2');
        $contadorPersonas->h3 = $request->input('h3');
        $contadorPersonas->h4 = $request->input('h4');
        $contadorPersonas->h5 = $request->input('h5');
        $contadorPersonas->h6 = $request->input('h6');
        $contadorPersonas->h7 = $request->input('h7');
        $contadorPersonas->h8 = $request->input('h8');
        $contadorPersonas->h9 = $request->input('h9');
        $contadorPersonas->h10 = $request->input('h10');
        $contadorPersonas->h11 = $request->input('h11');
        $contadorPersonas->h12 = $request->input('h12');
        $contadorPersonas->h13 = $request->input('h13');
        $contadorPersonas->h14 = $request->input('h14');
        $contadorPersonas->h15 = $request->input('h15');
        $contadorPersonas->h16 = $request->input('h16');
        $contadorPersonas->h17 = $request->input('h17');
        $contadorPersonas->h18 = $request->input('h18');
        $contadorPersonas->h19 = $request->input('h19');
        $contadorPersonas->h20 = $request->input('h20');
        $contadorPersonas->h21 = $request->input('h21');
        $contadorPersonas->h22 = $request->input('h22');
        $contadorPersonas->h23 = $request->input('h23');
        $contadorPersonas->h24 = $request->input('h24');
        $contadorPersonas->save();
        return response()->json("Contador de Personas Editado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ContadorPersonas::find($id)->delete();
        return response()->json("Contador de Personas Eliminado");
    }
}
