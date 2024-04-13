<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class postDoc extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "postDocs";

    protected $fillable = [
        'idUsuario',
        'status',
        'researcherInvolved',
        'nameOfPostdoc',
        'identification',
        'runOrPassport',
        'gender',
        'researchTopic',
        'supervisorName',
        'resourcesProvided',
        'fundingSource',
        'startYear',
        'endingYear',
        'startMonth',
        'endMonth',
        'institutionName',
        'comments',
        'progressReport',
        'personalEmail',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

}
