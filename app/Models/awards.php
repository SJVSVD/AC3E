<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class awards extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "awards";

    protected $fillable = [
        'status',
        'awardeeName',
        'awardName',
        'year',
        'contributionAwardee',
        'institution',
        'country',
        'progressReport',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'awardeeName', 'id');
    }
}
