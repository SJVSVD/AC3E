<?php

namespace App\Http\Controllers;
use App\Models\TIE_vendedores;
use Illuminate\Http\Request;

class crearVendedorController extends Controller
{

    public function store(Request $request)
    {
        $input = $request->all();
        $vendedor = TIE_vendedores::create($input);
    }

    public function verificarVendedor(Request $request)
    {
        $rut = $request['rut'];
        $existentes = TIE_vendedores::where('rut',$rut)->count();
        return response()->json($existentes); 
    }

}