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
        'idUsuario',
        'researcherInvolved',
        'awardeeName',
        'awardName',
        'year',
        'month',
        'contributionAwardee',
        'institution',
        'country',
        'progressReport',
        'comments'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
