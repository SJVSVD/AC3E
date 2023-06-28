<?php

namespace App\Http\Controllers;
use App\Models\SYS_marcas as Marcas;
use Illuminate\Http\Request;

class marcasController extends Controller
{
    public function index()
    {
        return Marcas::with('empresa')->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Marcas::create($input);

        return response()->json('Se ha guardado todo con exito');
    } 

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $marcas = Marcas::find($id);

        $input = $request->all();
        $marcas->update($input);
        return response()->json("Marca Editada");
    }

    public function destroy($id)
    {
        Marcas::find($id)->delete();
        return response()->json("Marca Eliminada");
    }
}
