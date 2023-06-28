<?php

namespace App\Http\Controllers;
use App\Models\SYS_empresa_marca as Empresa_Marca;
use Illuminate\Http\Request;

class empresasMarcasController extends Controller
{
    public function index()
    {
        return Empresa_Marca::with('empresa')->with('marca')->with('sucursal')->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Empresa_Marca::create($input);
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
        $empresaMarca = Empresa_Marca::find($id);

        $input = $request->all();
        $empresaMarca->update($input);
        return response()->json("Empresa-Marca Editada");
    }

    public function destroy($id)
    {
        Empresa_Marca::find($id)->delete();
        return response()->json("Empresa-Marca Eliminada");
    }
}
