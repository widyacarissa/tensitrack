@extends('layouts.admin')

@section('title', 'Tambah Tingkat Risiko')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <form action="{{ route('admin.risk-levels.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Kode (Otomatis) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode</label>
                    <input type="text" value="{{ $newCode }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm focus:border-[#E3943B] focus:ring-[#E3943B] sm:text-sm p-2">
                    <p class="mt-1 text-xs text-gray-500">Kode dibuat otomatis oleh sistem.</p>
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Tingkat Risiko</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] sm:text-sm p-2" placeholder="Contoh: Tinggi">
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] sm:text-sm p-2"></textarea>
                </div>

                <!-- Saran -->
                <div>
                    <label for="suggestion" class="block text-sm font-medium text-gray-700">Saran</label>
                    <textarea name="suggestion" id="suggestion" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] sm:text-sm p-2"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.risk-levels.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#E3943B] hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E3943B]">
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection