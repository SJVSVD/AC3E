<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class thesisStudent extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "thesisStudents";

    protected $fillable = [
        'idUsuario',
        'status',
        'identification',
        'studentName',
        'runOrPassport',
        'gender',
        'studentMail',
        'thesisStatus',
        'thesisTitle',
        'academicDegree',
        'degreeDenomination',
        'tutorName',
        'tutorInstitution',
        'cotutorName',
        'cotutorInstitution',
        'otherName',
        'otherInstitution',
        'university',
        'yearStart',
        'yearThesisEnd',
        'resourcesCenter',
        'posteriorArea',
        'institutionPosteriorArea',
        'comments',
        'thesisExtract',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
