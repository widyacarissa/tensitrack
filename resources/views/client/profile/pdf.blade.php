<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Hasil Skrining - TensiTrack</title>
    <style>
        @page {
            margin: 1cm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
            background-color: #fff; 
            margin: 0;
        }
        .container {
            width: 100%;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            color: #001B48;
        }
        .divider {
            border-top: 2px solid #001B48;
            margin: 15px 0;
        }
        .identity-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .identity-table td {
            padding: 3px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #001B48;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        .risk-level {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .risk-level-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .risk-level-name {
            font-size: 24px;
            font-weight: bold;
        }
        .risk-factors table {
            width: 100%;
            border-collapse: collapse;
        }
        .risk-factors th, .risk-factors td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .risk-factors th {
            background-color: #f2f2f2;
        }
        .suggestion {
            background: #e6f7ff;
            border-left: 4px solid #005f99;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('logo-tensitrack.png') }}" alt="TensiTrack Logo">
            <h1>TensiTrack - Laporan Hasil Skrining</h1>
        </div>

        <div class="divider"></div>

        <table class="identity-table">
            <tr>
                <td style="width: 15%"><strong>Nama</strong></td>
                <td style="width: 35%">: {{ $screening->client_name }}</td>
                <td style="width: 15%"><strong>Tanggal</strong></td>
                <td style="width: 35%">: {{ $screening->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Usia</strong></td>
                <td>: {{ $screening->snapshot_age }} Tahun</td>
                <td><strong>Tensi</strong></td>
                <td>: {{ $screening->snapshot_systolic }}/{{ $screening->snapshot_diastolic }} mmHg</td>
            </tr>
        </table>

        @if(!$screening->details->isEmpty())
        <div class="section risk-factors">
            <h2 class="section-title">Faktor Risiko Terpilih</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 35%">Nama Faktor Risiko</th>
                        <th style="width: 50%">Penjelasan Medis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($screening->details as $index => $detail)
                    <tr>
                        <td style="text-align: center; vertical-align: top;">{{ $loop->iteration }}</td>
                        <td style="vertical-align: top;">{{ $detail->riskFactor->code }}</td>
                        <td style="vertical-align: top;">{{ $detail->riskFactor->name }}</td>
                        <td style="vertical-align: top; text-align: justify;">
                            {{ $detail->riskFactor->medical_explanation ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="section">
            <h2 class="section-title">Hasil Analisis</h2>
            @php
                $r = strtolower($screening->result_level);
                $bg = '#f0fff4'; $color = '#2f855a'; // Default: Hijau
                
                if (stripos($r, 'tinggi') !== false) {
                    $bg = '#fff5f5'; $color = '#c53030'; // Merah
                } elseif (stripos($r, 'sedang') !== false) {
                    $bg = '#fffbeb'; $color = '#b7791f'; // Oranye
                } elseif (stripos($r, 'rendah') !== false) {
                    $bg = '#eff6ff'; $color = '#2563eb'; // Biru
                }
            @endphp
            <div class="risk-level" style="background-color: {{ $bg }}; color: {{ $color }};">
                <div class="risk-level-title">Tingkat Risiko Hipertensi</div>
                <div class="risk-level-name">{{ $screening->result_level }}</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Keterangan Medis</h2>
            <p style="text-align: justify;">
                {{ $riskLevel ? $riskLevel->description : 'Tidak ada keterangan tersedia.' }}
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">Saran & Rekomendasi</h2>
            <div class="suggestion">
                {!! $riskLevel ? nl2br(e($riskLevel->suggestion)) : 'Tidak ada saran tersedia.' !!}
            </div>
        </div>

        @if($screening->details->contains(fn($d) => !empty($d->riskFactor->recommendation)))
        <div class="section">
            <h2 class="section-title">Langkah Perbaikan Personal</h2>
            @foreach($screening->details as $detail)
                @if(!empty($detail->riskFactor->recommendation))
                <div style="margin-bottom: 10px; padding: 10px; background-color: #f0f9ff; border-left: 3px solid #001B48;">
                    <div style="font-weight: bold; font-size: 14px; margin-bottom: 3px; color: #001B48;">
                        Solusi untuk: {{ $detail->riskFactor->name }}
                    </div>
                    <div style="text-align: justify;">
                        {{ $detail->riskFactor->recommendation }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endif
        
        <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #888;">
            <p>Dicetak secara otomatis oleh sistem TensiTrack pada {{ date('d F Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
