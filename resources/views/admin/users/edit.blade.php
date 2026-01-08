@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Peran (Role)</label>
                    <select name="role" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2">
                        <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client (Pengguna Biasa)</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2" placeholder="Biarkan kosong jika tidak ingin mengubah">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#001B48] hover:bg-blue-900">Perbarui Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection