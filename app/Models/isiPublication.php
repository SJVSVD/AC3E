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
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
