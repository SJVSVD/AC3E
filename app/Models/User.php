<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

//Spatie
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LaravelPermissionToVueJS;

    protected $fillable = [
        'idColaborador',
        'name',
        'email',
        'rut',
        'password',
        'fofiAsignado',
        'idTipoUsuario'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function autorizador(){
        return $this->hasMany(User::class, 'id', 'idUsuario');
    }

    public function promotor(){
        return $this->hasOne(SYS_promotores::class, 'idUsuario', 'id');
    }

    public function factura(){
        return $this->hasMany(User::class, 'usuario', 'id');
    }

    public function grupos(){
        return $this->hasMany(SYS_grupos::class, 'idUsuario', 'id');
    }
    
    public function grupoLider(){
        return $this->hasMany(SYS_grupoLideres::class, 'idUsuario', 'id');
    }
    
    public function colaborador(){
        return $this->belongsTo(SYS_colaboradores::class, 'idColaborador', 'id');
    }
    
    public function timers(){
        return $this->hasMany(SYS_userRelatedTimers::class, 'idUsuario', 'id');
    }

    public function comisiones(){
        return $this->hasMany(ETC_comisiones::class, 'idUsuario', 'id');
    }

    public function empresasKam(){
        return $this->hasMany(SYS_kam::class, 'idUsuario', 'id');
    }

    public function robos(){
        return $this->hasMany(TIE_robos::class, 'idUsuario', 'id');
    }

    public function supervisores(){
        return $this->hasMany(SYS_supervisores::class, 'id', 'idUsuario');
    }

    public function promotores(){
        return $this->hasMany(SYS_promotores::class, 'id', 'idUsuario');
    }
}
