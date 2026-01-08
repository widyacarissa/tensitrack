<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Riwayat Skrining</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
        th { bg-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #001B48; }
        .header p { margin: 5px 0; font-size: 14px; color: #666; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-danger { color: #721c24; background-color: #f8d7da; }
        .badge-warning { color: #856404; background-color: #fff3cd; }
        .badge-success { color: #155724; background-color: #d4edda; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TensiTrack</h1>
        <p>Laporan Riwayat Skrining Hipertensi</p>
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Tanggal</th>
                <th style="width: 30%">Nama Client</th>
                <th style="width: 15%">Umur (Snapshot)</th>
                <th style="width: 30%">Hasil Risiko</th>
            </tr>
        </thead>
        <tbody>
            @foreach($screenings as $s)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $s->client_name }}</td>
                <td>{{ $s->snapshot_age ?? '-' }} Thn</td>
                <td>
                    @php
                        $class = 'badge-success';
                        if (stripos($s->result_level, 'Tinggi') !== false) $class = 'badge-danger';
                        elseif (stripos($s->result_level, 'Sedang') !== false) $class = 'badge-warning';
                    @endphp
                    <span class="badge {{ $class }}">{{ $s->result_level }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman <span class="pagenum"></span>
    </div>
</body>
</html>
