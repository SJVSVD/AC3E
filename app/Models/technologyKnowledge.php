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
        'researchLinesInvolved',
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
        'yearEnding',
        'monthEnding',
        'actualTrl'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

}
