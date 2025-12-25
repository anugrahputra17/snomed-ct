<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisComponentValue extends Model
{
    protected $fillable = [
        'value_text',
        'snomed_concept_id',
        'snomed_fsn',
    ];

    public function component()
    {
        return $this->belongsTo(DiagnosisComponent::class);
    }
}
