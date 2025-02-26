<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolController extends Controller
{

    // Función para mostrar registros y verificar si es administrador  o no lo es
    public function show($userId)
    {
        $user = User::find($userId);
        $roles = $user->roles;
        return $roles;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Función para almacenar un nuevo registro.
    public function store(Request $request)
    {
        $campos = [
            'name'=> 'required',
            'permission'=> 'required',
        ];
        $mensaje = [
            'name.required' => 'El campo "Nombre" es obligatorio.',
            'permission.required' => 'El campo "Permiso" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $role = Role::create(['name'=> $request ->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return response()->json("Rol Creado!");
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('roles.editar',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     // Función para editar un registro
     public function update(Request $request, $id)
     {
         // ✅ Validación correcta de los datos
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'permission' => 'array', // Asegurar que sea un array
         ]);
     
         // ✅ Buscar el rol y manejar error si no existe
         $role = Role::find($id);
         if (!$role) {
             return response()->json(['error' => 'Rol no encontrado'], 404);
         }
     
         // ✅ Actualizar nombre del rol
         $role->update(['name' => $validated['name']]);
     
         // ✅ Sincronizar permisos
         $role->syncPermissions($validated['permission']);
     
         return response()->json(['message' => 'Rol Editado con éxito']);
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     // Función para eliminar un registro
    public function destroy($id)
    {
        Role::find($id)->delete();
        return response()->json("Rol Eliminado");
    }
}