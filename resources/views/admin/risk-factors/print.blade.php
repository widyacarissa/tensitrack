<!DOCTYPE html>
<html>
<head>
    <title>Laporan Faktor Risiko</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; color: #001B48; }
        .header p { margin: 5px 0 0; color: #666; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Faktor Risiko TensiTrack</h1>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Kode</th>
                <th style="width: 30%">Nama Faktor</th>
                <th style="width: 55%">Pertanyaan Skrining</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riskFactors as $index => $factor)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $factor->code }}</td>
                <td>{{ $factor->name }}</td>
                <td>{{ $factor->question_text }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
