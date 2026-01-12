<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        'no_bpjs',
        'no_ktp',
        'name',
        'birth_place',
        'birth_date',
        'age',
        'gender',
        'religion',
        'ethnicity',
        'citizenship',
        'phone',
        'address',
    ];

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
