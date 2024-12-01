<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SessionLog extends Model {
    use HasFactory;

    protected $table = 'session_logs';

    // Definir los campos que se pueden llenar
    protected $fillable = [
        'user_id',
        'event_type',
        'description',
        'timestamp',
        'ip_address',
    ];

    // Relación con el modelo User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
