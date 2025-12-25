<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;

class DiagnosisPdfController extends Controller
{
    public function resume(Diagnosis $diagnosis)
    {
        $diagnosis->load([
        'patient',
        'components.values',
    ]);

    return Pdf::loadView('pdf.resume-medis', [
        'diagnosis' => $diagnosis,
    ])->stream('resume-medis.pdf');
    }
}
