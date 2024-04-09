<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class books extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "books";

    protected $fillable = [
        'status',
        'centerResearcher',
        'researcherInvolved',
        'workType',
        'bookAuthors',
        'chapterAuthors',
        'bookTitle',
        'chapterTitle',
        'firstPage',
        'lastPage',
        'editorialCityCountry',
        'year',
        'month',
        'progressReport',
        'editors',
        'ISBN',
        'comments'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'centerResearcher', 'id');
    }
}
