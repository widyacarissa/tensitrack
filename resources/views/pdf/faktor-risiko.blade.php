@extends('layouts.pdf-layout')
@section('title', 'Data Faktor Risiko')
@section('content')
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faktorRisiko as $fr)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $fr->kode }}</td>
                    <td>{{ $fr->name }}</td>
                    <td>{{ $fr->updated_at_formatted }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
