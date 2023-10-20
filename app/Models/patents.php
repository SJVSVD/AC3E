<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class patents extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "patents";

    protected $fillable = [
        'idUsuario',
        'status',
        'ipType',
        'authors',
        'nameOfResearch',
        'institutionOwner',
        'countryOfRegistration',
        'applicationDate',
        'grantDate',
        'applicationStatus',
        'registrationNumber',
        'state',
        'researcherInvolved',
        'comments',
        'progressReport',
        'nameOfPatent'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
