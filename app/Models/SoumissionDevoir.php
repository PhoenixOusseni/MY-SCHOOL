<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoumissionDevoir extends Model
{
    protected $fillable = [
        'date_submission',
        'content',
        'attachment',
        'status',
        'score',
        'feedback',
        'graded_by',
        'devoir_id',
        'eleve_id',
        'graded_at',
    ];

    // Relations
    public function devoir()
    {
        return $this->belongsTo(Devoir::class);
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(Enseignant::class, 'graded_by');
    }
}
