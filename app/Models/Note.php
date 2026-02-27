<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'score',
        'max_score',
        'percentage',
        'is_absent',
        'absence_justified',
        'comment',
        'entered_by',
        'evaluation_id',
        'eleve_id',
        'entered_at'
    ];

    protected $casts = [
        'is_absent' => 'boolean',
        'absence_justified' => 'boolean',
        'entered_at' => 'datetime',
    ];

    // Relations
    public function enteredBy()
    {
        return $this->belongsTo(Enseignant::class, 'entered_by');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
