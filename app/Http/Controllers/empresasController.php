<?php

namespace App\Http\Controllers;
use App\Models\SYS_empresas as Empresas;
use Illuminate\Http\Request;

class empresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Empresas::all();
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Empresas::create($input);
        return response()->json('Se ha guardado todo con exito');
        
    }    

    public function show($id)
    {
        return Empresas::find($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresas::find($id);
        $input = $request->all();
        $empresa->update($input);
        return response()->json("Empresa Editada");
    }

    public function destroy($id)
    {
        Empresas::find($id)->delete();
        return response()->json("Empresa Eliminada");
    }
}
