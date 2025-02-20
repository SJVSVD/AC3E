<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceDataCollectionMode extends Model
{
    protected $table = 'performance_data_collection_mode';

    protected $fillable = [
        'is_active',
        'deadline',
    ];

    public $timestamps = true;
}
