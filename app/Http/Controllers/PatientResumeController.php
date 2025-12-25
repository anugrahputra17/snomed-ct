<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientResumeController extends Controller
{
    public function pdf(Patient $patient)
    {
        $patient->load([
            'diagnoses.components.values'
        ]);

        return Pdf::loadView('pdf.resume-pasien', [
            'patient' => $patient
        ])->stream('resume-pasien.pdf');
    }
}
