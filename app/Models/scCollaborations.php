<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class scCollaborations extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "scCollaborations";

    protected $fillable = [
        'idUsuario',
        'status',
        'institutionCollaborates',
        'moduleType',
        'researcherInvolved',
        'studentOrResearcher',
        'activityType',
        'activityName',
        'otherActivity',
        'countryOrigin',
        'cityOrigin',
        'countryDestination',
        'cityDestination',
        'beginningDate',
        'endingDate',
        'progressReport',
        'nameOfAC3EMember',
        'nameOfExternalResearcher',
        'collaborationStay',
        'otherStay',
        'comments'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
