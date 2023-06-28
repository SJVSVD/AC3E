<?php

namespace App\Http\Controllers;
use App\Models\SYS_kam;
use Illuminate\Http\Request;

class empresasKamController extends Controller
{
    public function index(){
        return SYS_kam::with('usuario')->with('empresaKam')->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        SYS_kam::create($input);

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
        $EmpresaKam = SYS_kam::find($id);

        $input = $request->all();
        $EmpresaKam->update($input);
        return response()->json("Empresa Kam Editada");
    }

    public function destroy($id)
    {
        SYS_kam::find($id)->delete();
        return response()->json("Empresa-Marca Eliminada");
    }

}

