<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class universities extends Model
{
    use HasFactory;

    protected $table = "universities";

    protected $fillable = [
        'name',
    ];
}
