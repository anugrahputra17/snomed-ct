<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        'name',
        'birth_date',
        'gender',
        'phone',
        'address',
    ];

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
