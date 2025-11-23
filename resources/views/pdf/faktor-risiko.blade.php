@extends('layouts.pdf-layout')
@section('title', 'Data Faktor Risiko')
@section('content')
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>Nama</th>
                <th>Tanggal Dibuat/Diubah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faktorRisiko as $fr)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>{{ $fr['name'] }}</td>
                    <td>{{ $fr['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
