<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User as User;
use App\Models\SYS_userRelatedTimers as Timer;
use App\Models\SYS_sucursales as Sucursales;
use App\Mail\AvisoPermisos;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    public function index()
    {
        return User::with('roles')->with('permissions')->get();
    }



    public function email($email)
    {
        Mail::to($email)->send(new AvisoPermisos());
        return redirect()->back()->with('message', 'El correo se ha enviado con exito!');
    }

    public function cambiarEstadoUsuario($id){
        $user = User::find($id);
        if($user['estado'] == 'true'){
            $user['estado'] = false;
        }else{
            $user['estado'] = true;
        }
        $user -> save();
        return response()->json($user);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('usuarios.create',compact('roles'));
    }

    public function show($id)
    {
        $user = User::with('roles')->with('permissions')->find($id);
        return $user;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $contador = User::where('email',$input['email'])->count();

        if($contador > 0){
            return response()->json("email existente");
        }else{
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $user->save();
            $user->assignRole($request->input('roles'));
            $user->givePermissionTo($request->input('permisos'));
            return response()->json("Usuario Creado!");
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('usuarios.editar',compact('user','roles','userRole'));
    }

    public function updateUser(Request $request, $id)
    {
        $input = $request->all();
        $contador = User::where('email',$input['email'])->where('id', '!=', $id)->count();
        if($contador > 0){
            return response()->json("email existente");
        }else{
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input, array('password'));
            }
            $user = User::find($id);
            $mytime = Carbon::now();
            $user->updated_at = $mytime;
            $user->save();
            $user->update($input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
            $user->givePermissionTo($request->input('permisos'));
    
            return response()->json("Usuario Editado");
        }
    }
    
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json("Usuario Eliminado");
    }
    
    public function jsPermissions()
    {
        return json_encode([
                'roles' => $this->getRoleNames(),
                'permissions' => $this->getAllPermissions()->pluck('name'),
            ]);
    }

}
