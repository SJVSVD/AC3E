<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class organizationsScEvents extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "organizationsScEvents";
    protected $fillable = [
        'researchLinesInvolved',
        'status',
        'idUsuario',
        'researcherInvolved',
        'typeEvent',
        'other',
        'eventName',
        'country',
        'city',
        'startDate',
        'endingDate',
        'numberParticipants',
        'file',
        'is_link',
        'comments',
        'progressReport',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
