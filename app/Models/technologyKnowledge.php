<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class technologyKnowledge extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "technologyKnowledge";

    protected $fillable = [
        'idUsuario',
        'status',
        'technologyTransfer',
        'knowledgeTransfer',
        'typeOfTransfer',
        'nameOfBeneficiary',
        'country',
        'city',
        'placeRegion',
        'year',
        'month',
        'nameOfResearch',
        'comments',
        'progressReport',
        'description',
        'nameOfInstitutionInvolved',
        'researcherInvolved',
        'externalResearcher',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

}
