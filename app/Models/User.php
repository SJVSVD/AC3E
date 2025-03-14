<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Illuminate\Database\Eloquent\Builder;

// Spatie
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LaravelPermissionToVueJS;

    protected $fillable = [
        'idResearchLine',
        'idRole',
        'name',
        'email',
        'gender',
        'password',
        'estado',
        'notified_complete',
        'deadline'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function roleUser()
    {
        return $this->belongsTo(rolesUsers::class, 'idRole', 'id');
    }

    public function researchLine()
    {
        return $this->belongsTo(researchLines::class, 'idResearchLine', 'id');
    }
}
