@extends('layouts.pdf-layout')
@section('title', 'Data Tingkat Risiko')
@section('content')
    <table>
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>Nama</th>
                <th>Penyebab</th>
                <th>Solusi</th>
                <th>Tanggal Dibuat/Diubah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tingkatRisiko as $tr)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                                <td>{{ $tr['kode'] }}</td>
                                <td>{{ $tr['tingkat_risiko'] }}</td>
                                <td>{{ $tr['keterangan'] }}</td>
                                <td>{{ $tr['saran'] }}</td>                    <td>{{ $tr['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
