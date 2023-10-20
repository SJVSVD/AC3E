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
        'idUsuario',
        'status',
        'nameOfActivity',
        'developmentalStudies',
        'projectImplementation',
        'training',
        'prospectiveActivity',
        'installationOfScientist',
        'temporaryInstallation',
        'internationalAgreements',
        'participationsInInstances',
        'internationalExchange',
        'startDate',
        'endingDate',
        'resultsGoals',
        'nameOfOrganization',
        'countryOrigin',
        'privateSector',
        'academy1',
        'business',
        'ownEntrepreneurship',
        'publicSector',
        'government',
        'academy2',
        'socialOng',
        'education',
        'comments',
        'progressReport',
        'agentType',
        'typeOfConnection',
        'placeWhereWasExecuted',
        'internationalNational',
        'participationPublicPolicies',
        'researcherInvolved',
        'externalResearcher',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
