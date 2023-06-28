<?php

namespace App\Http\Controllers;
use App\Models\SYS_colaboradores;
use Illuminate\Http\Request;

class colabController extends Controller
{
    public function cambiarEstadoColab($id){
        $colab = SYS_colaboradores::find($id);
        if($colab['estado'] == 'true'){
            $colab['estado'] = false;
        }else{
            $colab['estado'] = true;
        }
        $colab -> save();
        return response()->json($colab);
    }

    public function index()
    {
        return SYS_colaboradores::with('user')->with('pais')->with('region')->with('comuna')->with('nacionalidad')->with('sistemaSalud')->with('afpAsociada')->with('estadoCivil')->with('departamento')->with('area')->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $colaborador = SYS_colaboradores::create($input);
        return response()->json("Colaborador Creado!");
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
        $input = $request->all();
        $colaborador = SYS_colaboradores::find($id);
        $colaborador->update($input);

        return response()->json("Usuario Editado");
    }

    public function destroy($id)
    {
        SYS_colaboradores::find($id)->delete();
        return response()->json("Colaborador Eliminado");
    }
}
