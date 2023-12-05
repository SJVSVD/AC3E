<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class isiPublication extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "isiPublications";

    protected $fillable = [
        'status',
        'idUsuario',
        'authors',
        'coauthor',
        'articleTitle',
        'journalName',
        'doi',
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
        'comments',
        'progressReport',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
