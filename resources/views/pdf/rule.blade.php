@extends('layouts.pdf-layout')
@section('title', 'Data Rule')
@section('content')
    <table>
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>Tingkat Risiko</th>
                <th>No Faktor Risiko</th>
                <th>Faktor Risiko</th>
                <th>Tanggal Dibuat/Diubah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rules as $rule)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>{{ $rule['tingkatRisiko']['tingkat_risiko'] }}</td>
                    <td>{{ $rule['no_faktor_risiko'] }}</td>
                    <td>{{ $rule['faktorRisiko']['name'] }}</td>
                    <td>{{ $rule['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
