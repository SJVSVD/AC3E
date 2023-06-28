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
        return User::with('roles')->with('permissions')->with('colaborador')->get();
    }

    public function getUsuariosViaTipo($idTipoUsuario)
    {
        return User::with('roles')->with('permissions')->with('colaborador')->where('idTipoUsuario', $idTipoUsuario)->get();
    }

    public function getUsuariosSinTipo()
    {
        return User::with('roles')->with('permissions')->with('colaborador')->whereNull('idTipoUsuario')->get();
    }

    // public function download()
    // {
    //     $usuarios = User::all();
    //     $pdf = \PDF::loadView('pages.pdf',compact('usuarios'));
        
    //     return $pdf->download('mi-archivo.pdf');
    // }

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
        $user = User::with('roles')->with('permissions')->with('timers')->with('colaborador')->with('colaborador.departamento')->with('colaborador.area')->with('colaborador.pais')->with('colaborador.region')->with('colaborador.comuna')
        ->with('colaborador.sistemaSalud')->with('colaborador.afpAsociada')->find($id);
        return $user;
    }

    public function checkTimer(Request $request)
    {   
        $data = $request->all();
        $timer = Timer::where('identificadorEspecifico', $data['identificadorEspecifico'])->where('timerName', $data['timerName'])->where('sistema', $data['sistema'])->where('idUsuario', $data['userID'])->first();
        return $timer;
    }

    public function store(Request $request)
    {
        $campos=[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirmpassword',
            'roles' => 'required',
        ];
        $mensaje = [
            'name.required' => 'El campo "Nombre" es obligatorio.',
            'email.required' => 'El campo "Email" es obligatorio.',
            'rut.unique' => 'El "Rut" ingresado ya esta siendo utilizado.',
            'password.required' => 'El campo "Contraseña" es obligatorio.',
            'roles.required' => 'El campo "Roles" es obligatorio.',
            'email.email' => 'El dato ingresado no es un correo electrónico.',
            'email.unique' => 'El "Email" ingresado ya esta siendo utilizado.',
            'password.same' => 'Las contraseñas no coinciden.'
        ];
        $profilePicture = $request['profilePicChange'];
        unset($request['profilePicChange']);
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        // CAMBIAR FOTO DE PERFIL
        if($profilePicture != null){
            $contenidoBinario = file_get_contents($profilePicture);
            $imagen = base64_encode($contenidoBinario);
            $user->profilePicture = $imagen;
        }
        $user->save();
        $user->assignRole($request->input('roles'));
        $user->givePermissionTo($request->input('permisos'));
        return response()->json("Usuario Creado!");
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('usuarios.editar',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function updateUser(Request $request, $id)
    {
        $campos=[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirmpassword',
        ];
        $mensaje = [
            'name.required' => 'El campo Nombre es obligatorio.',
            'email.required' => 'El campo Email es obligatorio.',
            'password.required' => 'El campo Contraseña es obligatorio.',
            'email.email' => 'El dato ingresado no es de tipo email',
            'email.unique' => 'El Email ingresado ya esta ocupado',
            'password.same' => 'Las contraseñas no coinciden'
        ];
        
        $profilePicture = $request['profilePicChange'];
        unset($request['profilePicChange']);
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));
        }
        $user = User::find($id);
        $mytime = Carbon::now();
        //$ideditor = $request->input('ideditor');
        $user->updated_at = $mytime;
        //$user->ultimo_editor = $ideditor;

        // CAMBIAR FOTO DE PERFIL
        if($profilePicture != null){
            $contenidoBinario = file_get_contents($profilePicture);
            $imagen = base64_encode($contenidoBinario);
            $user->profilePicture = $imagen;
        }
        $user->save();
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        $user->givePermissionTo($request->input('permisos'));

        return response()->json("Usuario Editado");
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

    public function superadmin()
    {   
        // Conseguir roles:
        $temporal = Role::select('id')->get();
        $roles = [];
        foreach ($temporal as $rol){
            array_push($roles, $rol['id']);
        }
        // Conseguir permisos:
        $temporal = Permission::select('id')->get();
        $permisos = [];
        foreach ($temporal as $permiso){
            array_push($permisos, $permiso['id']);
        }
        // Verificar si el usuario existe:
        $user = User::where('email', '=', 'superadministrador@amphora.cl')->first();
        if (!$user) {
            // Si no existe, crearlo:
            $usuario = ['name' => 'Super-Administrador', 'email' => 'superadministrador@amphora.cl', 'password' => '4mPH0R4.2o2E'];
            $usuario['password'] = Hash::make($usuario['password']);
            $user = User::create($usuario);
        }
        // Asignar roles:
        $user->assignRole($roles);
        // Asignar permisos a "Super Administrador":
        $role = Role::where('name', '=', 'Super-Administrador')->first();
        $role->syncPermissions($permisos);
        return response()->json("Super-Administrador Creado.");
    }

    public function getProfilePic($id){
        $user = User::find($id);
        return $user['profilePicture'];
    }

    public function changeProfilePic(Request $request){
        $idUsuario = $request['userID'];
        $fotoPerfil = $request['profilePicChange'];
        $user = User::find($idUsuario);
        $contenidoBinario = file_get_contents($fotoPerfil);
        $imagen = base64_encode($contenidoBinario);
        $user->profilePicture = $imagen;
        $user->save();
        return "Foto actualizada con éxito.";
    }

    public function deleteProfilePic($id){
        $user = User::find($id);
        $user->profilePicture = null;   
        $user->save();
        return "Foto de perfil eliminada con éxito.";
    }
}
