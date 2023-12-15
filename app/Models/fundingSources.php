<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class fundingSources extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "fundingSources";

    protected $fillable = [
        'idUsuario',
        'status',
        'researcherInvolved',
        'typeSources',
        'nameOfInstitution',
        'programContest',
        'projectTitle',
        'principalResearcher',
        'startDate',
        'finishDate',
        'comments',
        'inCash',
        'typeOfCollaboration',
        'progressReport',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
