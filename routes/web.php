<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosisPdfController;
use App\Http\Controllers\PatientResumeController;

Route::get('/', fn () => redirect('/admin'));

Route::get(
    '/diagnosis/{diagnosis}/resume-pdf',
    [DiagnosisPdfController::class, 'resume']
)->name('diagnosis.resume.pdf');

Route::get(
    '/patients/{patient}/resume-pdf',
    [PatientResumeController::class, 'pdf']
)->name('patient.resume.pdf');