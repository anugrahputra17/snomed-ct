<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume Medis</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 0;
            vertical-align: top;
        }

        ul {
            margin: 5px 0 10px 15px;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <h2>RUMAH SAKIT CONTOH</h2>
    <small>Jl. Contoh No. 123 — Telp. (021) 123456</small>
</div>

{{-- ================= DATA PASIEN ================= --}}
<div class="section">
    <div class="section-title">DATA PASIEN</div>
    <table>
        <tr>
            <td width="25%">Nama</td>
            <td>: {{ $diagnosis->patient->name }}</td>
        </tr>
        <tr>
            <td>No Rekam Medis</td>
            <td>: {{ $diagnosis->patient->medical_record_number }}</td>
        </tr>
        <tr>
            <td>No BPJS</td>
            <td>: {{ $diagnosis->patient->no_bpjs }}</td>
        </tr>
        <tr>
            <td>No KTP</td>
            <td>: {{ $diagnosis->patient->no_ktp }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $diagnosis->created_at->format('d M Y') }}</td>
        </tr>
    </table>
</div>

{{-- ================= RINGKASAN ================= --}}
<div class="section">
    <div class="section-title">RINGKASAN DIAGNOSIS</div>
    <p>{{ $diagnosis->diagnosis_text }}</p>
</div>

{{-- ================= DETAIL DIAGNOSIS ================= --}}
<div class="section">
    <div class="section-title">DETAIL DIAGNOSIS (SNOMED CT)</div>

    @foreach ($diagnosis->components->groupBy('variable') as $variable => $components)
        <strong>{{ $variable }}</strong>
        <ul>
            @foreach ($components as $component)
                @foreach ($component->values as $value)
                    <li>
                        {{ $value->snomed_fsn }}
                        @if($value->value_text)
                            — {{ $value->value_text }}
                        @endif
                    </li>
                @endforeach
            @endforeach
        </ul>
    @endforeach
</div>

{{-- ================= FOOTER ================= --}}
<div class="section" style="margin-top:40px;">
    <table width="100%">
        <tr>
            <td width="60%"></td>
            <td align="center">
                Dokter Pemeriksa<br><br><br>
                _______________________
            </td>
        </tr>
    </table>
</div>

</body>
</html>
