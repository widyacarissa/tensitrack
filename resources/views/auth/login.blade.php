@extends('layouts.guest')

@section('title', 'Login')

@section('header')
    <h2 class="text-white text-lg font-medium">Selamat Datang Kembali</h2>
    <p class="text-blue-200 text-sm">Masuk untuk mengelola kesehatan Anda.</p>
@endsection

@section('main')
    {{-- Session Status --}}
    @if (session('status'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 text-sm mb-6 rounded-r">
            {{ session('status') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 text-sm mb-6 rounded-r flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
            <div>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-[#001B48] mb-2">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input id="email" type="email" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-bold text-[#001B48] mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input id="password" type="password" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#E3943B] focus:ring-[#E3943B] transition shadow-sm text-sm @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="form-checkbox h-4 w-4 text-[#E3943B] border-gray-300 rounded focus:ring-[#E3943B] focus:ring-offset-0" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600 font-medium">Ingat Saya</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-[#E3943B] hover:text-orange-700 font-bold" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-[#001B48] hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-200 transition transform hover:-translate-y-0.5">
            Masuk Sekarang
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 mt-8">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-[#E3943B] hover:text-orange-700 ml-1">Daftar disini</a>
            </p>
        @endif
    </form>

    <!-- Inisialisasi Ikon Lucide manual karena di layout auth mungkin belum ada scriptnya -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
@endsection