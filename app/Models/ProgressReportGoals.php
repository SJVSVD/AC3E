<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressReportGoals extends Model {
    use HasFactory;

    protected $fillable = ['goals', 'research_line_id', 'user_id', 'researcher_type_id','module'];

    protected $casts = [
        'goals' => 'array', // Indica que el campo JSON debe ser tratado como un array en PHP
    ];

    public function researchLine() {
        return $this->belongsTo(researchLines::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
