@extends('layouts.admin')

@section('title', 'Tambah Faktor Risiko')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <form action="{{ route('admin.risk-factors.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Kode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode</label>
                    <input type="text" value="{{ $newCode }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm p-2">
                </div>

                <!-- Nama Faktor -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Faktor Risiko</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2">
                </div>

                <!-- Pertanyaan -->
                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Teks Pertanyaan (untuk Skrining)</label>
                    <textarea name="question_text" id="question_text" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2" placeholder="Contoh: Apakah Anda sering merasa pusing?"></textarea>
                </div>

                <!-- Penjelasan Medis -->
                <div>
                    <label for="medical_explanation" class="block text-sm font-medium text-gray-700">Penjelasan Medis</label>
                    <textarea name="medical_explanation" id="medical_explanation" rows="5" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2" placeholder="Jelaskan mengapa faktor risiko ini penting secara medis."></textarea>
                </div>

                <!-- Rekomendasi / Solusi -->
                <div>
                    <label for="recommendation" class="block text-sm font-medium text-gray-700">Langkah Perbaikan Personal (Solusi)</label>
                    <textarea name="recommendation" id="recommendation" rows="5" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2" placeholder="Saran spesifik yang akan muncul jika user memiliki faktor risiko ini."></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.risk-factors.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#E3943B] hover:bg-orange-600">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection