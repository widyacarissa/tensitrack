@extends('layouts.pdf-layout')
@section('title', 'Data Tingkat Risiko')
@section('content')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tingkat Risiko</th>
                <th>Keterangan</th>
                <th>Saran</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tingkatRisiko as $tr)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tr->kode }}</td>
                    <td>{{ $tr->tingkat_risiko }}</td>
                    <td>{{ $tr->keterangan }}</td>
                    <td>{{ $tr->saran }}</td>
                    <td>{{ $tr->updated_at_formatted }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
