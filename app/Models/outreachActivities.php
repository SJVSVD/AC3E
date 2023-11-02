<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class outreachActivities extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "outreachActivities";

    protected $fillable = [
        'idUsuario',
        'status',
        'activityType',
        'otherType',
        'activityName',
        'activityDescription',
        'date',
        'attendantsAmount',
        'duration',
        'country',
        'placeRegion',
        'city',
        'undergraduateStudents',
        'primaryEducationStudents',
        'secondaryEducationStudents',
        'generalCommunity',
        'companiesIndustriesServices',
        'schoolTeachers',
        'governmentOfficial',
        'other',
        'nameOfTheMainResponsible',
        'progressReport',
        'researcherInvolved',
        'externalResearcher',
        'responsability',
        'comments',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
