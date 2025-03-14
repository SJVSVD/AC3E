<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class participationScEvents extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "participationScEvents";
    protected $fillable = [
        'researchLinesInvolved',
        'idUsuario',
        'status',
        'researcherInvolved',
        'presentationTitle',
        'typeOfParticipation',
        'otherParticipation',
        'typeEvent',
        'other',
        'eventName',
        'country',
        'city',
        'startDate',
        'endingDate',
        'nameOfParticipants',
        'progressReport',
        'file',
        'is_link',
        'comments',
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
