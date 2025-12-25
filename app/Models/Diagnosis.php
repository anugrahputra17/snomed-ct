<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'patient_id',
        'diagnosis_text',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function components()
    {
        return $this->hasMany(DiagnosisComponent::class);
    }
}
