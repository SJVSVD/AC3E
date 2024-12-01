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
        'researchLinesInvolved',
        'idUsuario',
        'status',
        'ipType',
        'authors',
        'institutionOwner',
        'countryOfRegistration',
        'applicationDate',
        'grantDate',
        'applicationStatus',
        'registrationNumber',
        'applicationGranted',
        'nameOfPatent',
        'researcherInvolved',
        'comments',
        'progressReport',
        'nameOfPatent'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
