<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Skrining</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; color: #001B48; }
        .header p { margin: 5px 0 0; color: #666; }
        .badge { padding: 2px 6px; border-radius: 4px; color: #fff; font-size: 10px; font-weight: bold; display: inline-block; }
        .bg-red { background-color: #dc2626; }
        .bg-yellow { background-color: #d97706; }
        .bg-blue { background-color: #2563eb; }
        .bg-green { background-color: #16a34a; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Riwayat Skrining TensiTrack</h1>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Nama Client</th>
                <th style="width: 5%">Usia</th>
                <th style="width: 10%">Tensi</th>
                <th style="width: 25%">Faktor Risiko</th>
                <th style="width: 20%">Hasil Risiko</th>
            </tr>
        </thead>
        <tbody>
            @foreach($screenings as $index => $s)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $s->client_name }}</td>
                <td class="text-center">{{ $s->snapshot_age }}</td>
                <td class="text-center">{{ $s->snapshot_systolic }}/{{ $s->snapshot_diastolic }}</td>
                <td>
                    @if($s->details->isEmpty())
                        <span style="color: #999; font-style: italic;">-</span>
                    @else
                        <ol style="margin: 0; padding-left: 15px; font-size: 11px;">
                            @foreach($s->details as $d)
                                <li>{{ $d->riskFactor->name ?? '-' }}</li>
                            @endforeach
                        </ol>
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $r = strtolower($s->result_level);
                        $color = 'bg-green';
                        
                        if (stripos($r, 'tinggi') !== false) $color = 'bg-red';
                        elseif (stripos($r, 'sedang') !== false) $color = 'bg-yellow';
                        elseif (stripos($r, 'rendah') !== false) $color = 'bg-blue';
                    @endphp
                    <span class="badge {{ $color }}">{{ $s->result_level }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>