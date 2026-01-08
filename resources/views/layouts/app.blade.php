<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TensiTrack')</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @php
        $isProduction = app()->environment('production');
        $manifestPath = $isProduction ? base_path('../public_html/build/manifest.json') : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
        @php
            $manifest = json_decode(file_get_contents($manifestPath), true);
        @endphp
        <link rel="stylesheet" href="{{ config('app.url') }}/build/{{ $manifest['resources/css/app.css']['file'] }}">
        <script type="module" src="{{ config('app.url') }}/build/{{ $manifest['resources/js/app.js']['file'] }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Custom Scrollbar handled by Tailwind/CSS file if needed, keeping consistent with guest layout */
    </style>
</head>
<body x-data="{ mobileMenuOpen: false, splashActive: {{ (session('success') || session('error') || session('status') || request()->routeIs('client.profile.index')) ? 'false' : 'true' }}, ...$store.global }" class="bg-gray-50 text-[#001B48] text-base antialiased flex flex-col min-h-screen">

    <!-- Splash Screen -->
    <div x-show="splashActive"
         x-init="
            @if(session('success') || session('error') || session('status') || request()->routeIs('client.profile.index'))
                splashActive = false;
            @else
                let duration = sessionStorage.getItem('tensi_splash_seen') ? 500 : 1500;
                sessionStorage.setItem('tensi_splash_seen', 'true');
                setTimeout(() => splashActive = false, duration);
            @endif
         "
         x-transition:leave="transition ease-out duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#001B48] text-white w-full h-full"> 
        
        <div class="relative flex items-center justify-center mb-6">
            <!-- Pulsing Effect -->
            <div class="absolute w-32 h-32 bg-[#E3943B] rounded-full opacity-20 animate-ping"></div>
            <!-- Logo -->
            <div class="relative z-10 bg-white p-4 rounded-full shadow-2xl animate-heartbeat">
                <img src="/logo.png" class="h-16 w-auto" alt="TensiTrack Logo">
            </div>
        </div>

        <h1 class="text-3xl font-bold tracking-wider mb-2 animate-pulse">
            Tensi<span class="text-[#E3943B]">Track</span>
        </h1>
        <p class="text-blue-200 text-sm tracking-[0.3em] uppercase font-light">Deteksi Dini, Hidup Sehat</p>
        
        <!-- Loading Bar -->
        <div class="mt-8 w-32 h-1 bg-blue-900/50 rounded-full overflow-hidden">
            <div class="h-full bg-[#E3943B] animate-[slideRight_1s_ease-in-out_infinite]"></div>
        </div>
    </div>

    <!-- Main Application Content (Hidden until Splash Ends) -->
    <div x-show="!splashActive" 
         x-cloak
         x-transition:enter="transition ease-in duration-700"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="flex flex-col min-h-screen">

        <!-- Navbar (Biru) -->
    <nav class="bg-[#001B48] sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" @click.prevent="window.location.pathname === '/' ? window.scrollTo({ top: 0, behavior: 'smooth' }) : window.location.href = '{{ route('home') }}'" class="flex items-center">
                        <img src="/logo.png" alt="Logo" class="h-14 w-auto">
                        <span class="ml-3 text-2xl font-bold text-white tracking-wide">Tensi<span class="text-[#E3943B]">Track</span></span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="{{ route('home') }}" @click.prevent="window.location.pathname === '/' ? window.scrollTo({ top: 0, behavior: 'smooth' }) : window.location.href = '{{ route('home') }}'" class="px-3 py-2 rounded-md text-base font-bold transition-colors whitespace-nowrap text-[#E3943B] hover:text-white">Home</a>
                        
                        <!-- Dropdown Layanan -->
                        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                            <button class="text-[#E3943B] hover:text-white px-3 py-2 rounded-md text-base font-bold transition-colors flex items-center whitespace-nowrap">
                                Layanan <i data-lucide="chevron-down" class="ml-1 w-4 h-4"></i>
                            </button>
                            
                            <div x-show="open" x-transition.opacity.duration.200
                                 class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    <button @click="if(window.location.pathname !== '/') { window.location.href = '/#kalkulator-bmi'; } else { document.getElementById('kalkulator-bmi').scrollIntoView({ behavior: 'smooth', block: 'center' }); open = false; }" class="block w-full text-left px-4 py-2 text-base text-gray-700 hover:bg-gray-100" role="menuitem">Kalkulator BMI</button>
                                    <a href="/#diagnosis" class="block w-full text-left px-4 py-2 text-base text-gray-700 hover:bg-gray-100" role="menuitem">Skrining Hipertensi</a>
                                </div>
                            </div>
                        </div>

                        <a href="/#alur-interaksi" @click.prevent="if(window.location.pathname !== '/') { window.location.href = '/#alur-interaksi'; } else { document.getElementById('alur-interaksi').scrollIntoView({ behavior: 'smooth', block: 'center' }); }" class="text-[#E3943B] hover:text-white px-3 py-2 rounded-md text-base font-bold transition-colors whitespace-nowrap">Alur Kerja</a>
                    </div>
                </div>

                <!-- Auth Buttons (Desktop) -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-full font-bold transition flex items-center {{ request()->routeIs('admin.dashboard') ? 'text-[#001B48] bg-white' : 'text-white bg-[#E3943B] hover:bg-orange-600' }}">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('client.profile.index') }}" class="px-4 py-2 rounded-full font-bold transition flex items-center {{ request()->routeIs('client.profile.index') ? 'text-[#001B48] bg-white' : 'text-white bg-[#E3943B] hover:bg-orange-600' }}">
                                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profil Saya
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-[#E3943B] text-white px-6 py-2 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                                Keluar
                            </button>
                        </form>
                    @else
                                            <a href="{{ route('login') }}" class="bg-[#E3943B] text-white px-6 py-2 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                                                Login/Daftar
                                            </a>                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-[#001B48] inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none">
                        <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse class="md:hidden bg-[#001B48] border-t border-white/10 fixed w-full top-20 left-0 z-40 shadow-xl overflow-y-auto max-h-[calc(100vh-5rem)]">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="/" class="text-[#E3943B] hover:text-white block px-3 py-3 rounded-md text-base font-bold border-b border-white/5">Home</a>
                <button @click="if(window.location.pathname !== '/') { window.location.href = '/#kalkulator-bmi'; } else { document.getElementById('kalkulator-bmi').scrollIntoView({ behavior: 'smooth', block: 'center' }); mobileMenuOpen = false; }" class="text-[#E3943B] hover:text-white block w-full text-left px-3 py-3 rounded-md text-base font-bold border-b border-white/5">Kalkulator BMI</button>
                
                @auth
                    <a href="/#diagnosis" class="text-[#E3943B] hover:text-white block w-full text-left px-3 py-3 rounded-md text-base font-bold border-b border-white/5">Skrining Hipertensi</a>
                @else
                    <a href="{{ route('login') }}" class="text-[#E3943B] hover:text-white block px-3 py-3 rounded-md text-base font-bold border-b border-white/5">Skrining Hipertensi</a>
                @endauth
                
                <button @click="if(window.location.pathname !== '/') { window.location.href = '/#alur-interaksi'; } else { document.getElementById('alur-interaksi').scrollIntoView({ behavior: 'smooth', block: 'center' }); mobileMenuOpen = false; }" class="text-[#E3943B] hover:text-white block w-full text-left px-3 py-3 rounded-md text-base font-bold border-b border-white/5">Alur Kerja</button>

                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="text-white bg-[#E3943B] block px-3 py-3 rounded-md text-base font-medium mt-6 text-center shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('client.profile.index') }}" @click="mobileMenuOpen = false" class="text-white bg-[#E3943B] block px-3 py-3 rounded-md text-base font-medium mt-6 text-center shadow-md">Profil Saya</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-white bg-[#001B48] border border-white/20 block px-3 py-3 rounded-md text-base font-medium mt-3 text-center hover:bg-white/10">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="text-white bg-[#E3943B] block px-3 py-3 rounded-md text-base font-medium mt-6 text-center shadow-md">Login/Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (Hidden on Screening Page) -->
    @if (!request()->is('screening*'))
    <footer class="bg-[#001B48] text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-12 gap-8 mb-8">
                
                <!-- Column 1: Logo & Explanation (Full width on mobile) -->
                <div class="col-span-2 lg:col-span-5">
                    <a href="/" class="flex items-center mb-4">
                        <img src="/logo.png" alt="Logo" class="h-10 w-auto">
                        <span class="ml-2 text-xl font-bold text-white tracking-wide">Tensi<span class="text-[#E3943B]">Track</span></span>
                    </a>
                    <p class="text-sm text-gray-300 leading-relaxed max-w-md">
                        TensiTrack membantu Anda memprediksi potensi risiko hipertensi berdasarkan gaya hidup Anda.
                        Deteksi dini untuk hidup yang lebih sehat.
                    </p>
                </div>

                <!-- Column 2: Tautan Cepat -->
                <div class="col-span-1 lg:col-span-2">
                    <h4 class="text-lg font-bold text-white mb-4">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-300 hover:text-white transition-colors text-sm">Home</a></li>
                        <li><a href="/#alur-interaksi" class="text-gray-300 hover:text-white transition-colors text-sm">Alur Kerja</a></li>
                        <li><a href="/#faq" class="text-gray-300 hover:text-white transition-colors text-sm">FAQ</a></li>
                    </ul>
                </div>

                <!-- Column 3: Layanan Kami -->
                <div class="col-span-1 lg:col-span-2">
                    <h4 class="text-lg font-bold text-white mb-4">Layanan Kami</h4>
                    <ul class="space-y-3">
                        <li><a href="/#kalkulator-bmi" class="text-gray-300 hover:text-white transition-colors text-sm">Kalkulator BMI</a></li>
                        <li><a href="/#diagnosis" class="text-gray-300 hover:text-white transition-colors text-sm">Skrining Hipertensi</a></li>
                    </ul>
                </div>

                <!-- Column 4: Kontak Kami (Full width on mobile) -->
                <div class="col-span-2 lg:col-span-3">
                    <h4 class="text-lg font-bold text-white mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li class="flex items-center"><i data-lucide="mail" class="w-4 h-4 mr-2 text-[#E3943B]"></i> info@tensitrack.com</li>
                            <li class="flex items-center"><i data-lucide="phone" class="w-4 h-4 mr-2 text-[#E3943B]"></i> +62 852-7333-7881</li>
                        <li class="flex items-start"><i data-lucide="map-pin" class="w-4 h-4 mr-2 text-[#E3943B] mt-1 shrink-0"></i> Jl. Terusan Ryacudu Way Hui, Kecamatan Jati Agung, Lampung Selatan</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 pt-6 text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} TensiTrack. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>