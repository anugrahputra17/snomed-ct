<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'patient_id',
        'diagnosis_text',
        'snomed_code',
        'snomed_term',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

