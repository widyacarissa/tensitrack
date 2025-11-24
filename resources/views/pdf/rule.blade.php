@extends('layouts.pdf-layout')
@section('title', 'Data Aturan Tingkat Risiko')
@section('content')
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tingkat Risiko (Kode - Nama)</th>
                <th>Daftar Faktor Risiko (Kode - Nama)</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($groupedRules as $group)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $group['tingkatRisiko']->kode }} - {{ $group['tingkatRisiko']->tingkat_risiko }}</td>
                    <td>
                        <ul>
                            @foreach ($group['faktorRisiko'] as $fr)
                                <li>{{ $fr->kode }} - {{ $fr->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $group['latest_update_formatted'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection