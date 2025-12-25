<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume Medis Pasien</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>RESUME MEDIS PASIEN</h2>

<p>
    <strong>Nama:</strong> {{ $patient->name }} <br>
    <strong>No RM:</strong> {{ $patient->medical_record_number }}
</p>

<hr>

@foreach ($patient->diagnoses as $diagnosis)

    <div class="section">
        <strong>Tanggal:</strong> {{ $diagnosis->created_at->format('d M Y') }}<br>
        <strong>Ringkasan:</strong> {{ $diagnosis->diagnosis_text }}

        <br><br>

        @foreach ($diagnosis->components->groupBy('variable') as $variable => $components)
            <strong>{{ $variable }}</strong>
            <ul>
                @foreach ($components as $component)
                    @foreach ($component->values as $value)
                        <li>{{ $value->snomed_fsn }}</li>
                    @endforeach
                @endforeach
            </ul>
        @endforeach
    </div>

    <hr>

@endforeach

</body>
</html>
