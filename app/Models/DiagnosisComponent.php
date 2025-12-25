<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisComponent extends Model
{
    protected $fillable = [
        'diagnosis_id',
        'component',
        'variable',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function values()
    {
        return $this->hasMany(DiagnosisComponentValue::class);
    }
}
