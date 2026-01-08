<!DOCTYPE html>
<html>
<head>
    <title>Laporan Aturan Diagnosa</title>
    <!-- Import Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            font-size: 12px; 
            color: #333; 
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 20px; 
            color: #001B48; 
            font-weight: 700;
        }
        .header p { 
            margin: 5px 0 0; 
            color: #666; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px 8px; 
            text-align: center; /* Rata Tengah */
        }
        th { 
            background-color: #f2f2f2; /* Header Abu-abu */
            font-weight: bold; 
            color: #333;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Aturan Diagnosa TensiTrack</h1>
        <p>Laporan Konfigurasi Sistem Pakar • Dicetak pada {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%">Prioritas</th> <!-- Nama Kolom Prioritas -->
                <th style="width: 10%">Kode</th>
                <th style="width: 23%">Hasil Risiko</th>
                <th style="width: 30%">Kondisi Faktor Utama</th>
                <th style="width: 12.5%">Min. Lain</th>
                <th style="width: 12.5%">Max. Lain</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rules as $rule)
            <tr>
                <td>{{ $rule->priority }}</td>
                <td style="font-weight: 600;">{{ $rule->code }}</td>
                <td>{{ $rule->riskLevel->name }}</td> <!-- Tanpa Warna -->
                <td>
                    @if($rule->riskFactors->count() > 0)
                        <strong>{{ $rule->operator }}</strong>: 
                        {{ $rule->riskFactors->pluck('code')->join(', ') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $rule->min_other_factors }}</td>
                <td>{{ $rule->max_other_factors >= 99 ? '∞' : $rule->max_other_factors }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
