<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class publicPrivate extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "publicPrivate";
    protected $fillable = [
        'researchLinesInvolved',
        'idUsuario',
        'status',
        'nameOfActivity',
        'agentType',
        'typeOfConnection',
        'placeWhereWasExecuted',
        'internationalNational',
        'participationPublicPolicies',
        'researcherInvolved',
        'externalResearcher',
        'startDate',
        'endingDate',
        'resultsGoals',
        'nameOfOrganization',
        'countryOrigin',
        'comments',
        'progressReport',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
