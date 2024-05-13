<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermisosController extends Controller
{
    public function index(){
        return Permission::get();
    }

    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $campos = [
            'name' => 'required',
        ];
        $mensaje = [
            'name.required' => 'El campo "Nombre" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $role = Permission::create($input);
        return response()->json("Permiso Creado!");
    }
    
    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userId){
        $user = User::find($userId);
        $permissions = $user->permissions;
        return $permissions;
    }

    public function getPermisosRol($id){
        $permisos = Role::with('permissions')->where('id',$id)->get();
        $permisosRol = [];
        foreach ($permisos[0]['permissions'] as $permiso){
            array_push($permisosRol, $permiso['name']);
        }
        return $permisosRol;
    }
}
