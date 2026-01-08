@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('header')
    <h2 class="text-white text-lg font-medium">Bergabunglah Bersama Kami</h2>
    <p class="text-blue-200 text-xs mt-1">Buat akun baru untuk memulai perjalanan sehat Anda.</p>
@endsection

@section('main')
    {{-- Session Status --}}
    @if (session('status'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-2 text-xs mb-4 rounded-r">
            {{ session('status') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-2 text-xs mb-4 rounded-r flex items-start">
            <i data-lucide="alert-circle" class="w-4 h-4 mr-2 mt-0.5"></i>
            <div>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="full_name" class="block text-xs font-bold text-[#001B48] mb-1">Nama Lengkap</label>
            <input id="full_name" type="text" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm @error('full_name') border-red-500 @enderror" name="full_name" value="{{ old('full_name') }}" required autocomplete="name" placeholder="Nama Lengkap Anda">
            @error('full_name')
                <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-[#001B48] mb-1">Email</label>
            <input id="email" type="email" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
            @error('email')
                <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Grid -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-[#001B48] mb-1">Password</label>
                <input id="password" type="password" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
            </div>

            <!-- Password Confirm -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-[#001B48] mb-1">Konfirmasi</label>
                <input id="password_confirmation" type="password" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>
        @error('password')
            <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
        @enderror

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-[#001B48] hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-200 transition transform hover:-translate-y-0.5 mt-4">
            Daftar Akun
        </button>

        <!-- Login Link -->
        <p class="text-center text-xs text-gray-600 mt-3">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-bold text-[#E3943B] hover:text-orange-700 ml-1">Masuk disini</a>
        </p>
    </form>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
@endsection