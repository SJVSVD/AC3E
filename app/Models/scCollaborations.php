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
        'peopleInvolved',
        'activityType',
        'collaborationType',
        'otherCollaboration',
        'activityName',
        'countryOrigin',
        'cityOrigin',
        'countryDestination',
        'cityDestination',
        'beginningDate',
        'endingDate',
        'nameOfResearch',
        'progressReport',
        'nameOfAC3EMember',
        'nameOfExternalResearcher',
        'collaborationStay',
        'otherStay',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
