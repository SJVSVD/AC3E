<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rolesUsers extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "rolesUsers";

    protected $fillable = [
        'name',
    ];
}
