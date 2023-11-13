<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class nonIsiPublication extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "nonIsiPublications";

    protected $fillable = [
        'status',
        'idUsuario',
        'authors',
        'indexedBy',
        'articleTitle',
        'journalName',
        'volume',
        'firstPage',
        'lastPage',
        'yearPublished',
        'funding',
        'mainResearchers',
        'associativeResearchers',
        'postDoc',
        'thesisStudents',
        'nationalExternalResearchers',
        'internationalExternalResearchers',
        'researcherInvolved',
        'file',
        'comments',
        'progressReport'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
