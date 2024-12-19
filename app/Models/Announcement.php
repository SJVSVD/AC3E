<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'message',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $dates = ['start_time', 'end_time', 'deleted_at'];
}
