<?php

namespace App\Http\Controllers;
use App\Models\SYS_bancos;
use Illuminate\Http\Request;

class bancosController extends Controller
{
    public function index()
    {
        return SYS_bancos::all();
    }

    public function store(Request $request)
    {
        $input = $request->all();
        SYS_bancos::create($input);
        return response()->json('Se ha guardado todo con exito');
    }    

    public function update(Request $request, $id)
    {
        $banco = SYS_bancos::find($id);
        $input = $request->all();
        $banco->update($input);
        return response()->json("Banco Editado");
    }

    public function destroy($id)
    {
        SYS_bancos::find($id)->delete();
        return response()->json("Banco Eliminado");
    }
}
