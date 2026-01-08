<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Mobile')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>
<body class="bg-gray-100 text-[#001B48] antialiased flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-[#001B48] text-white sticky top-0 z-50 shadow-md">
        <div class="px-4 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <img src="/logo.png" alt="Logo" class="h-8 w-auto mr-2 bg-white rounded-full p-0.5">
                <span class="font-bold tracking-wide">Admin<span class="text-[#E3943B]">Panel</span></span>
            </div>
            
            <!-- Profile / Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 text-white transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pb-24">
        @yield('content')
    </main>

    <!-- Bottom Navigation (Admin) -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 z-40 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <div class="grid grid-cols-4 h-16">
            
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center text-[10px] font-bold transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-[#001B48]' : 'text-gray-400 hover:text-gray-600' }}">
                <i data-lucide="layout-dashboard" class="w-6 h-6 mb-1 {{ request()->routeIs('admin.dashboard') ? 'fill-current/10' : '' }}"></i>
                Dashboard
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center text-[10px] font-bold transition-colors {{ request()->routeIs('admin.users.*') ? 'text-[#001B48]' : 'text-gray-400 hover:text-gray-600' }}">
                <i data-lucide="users" class="w-6 h-6 mb-1 {{ request()->routeIs('admin.users.*') ? 'fill-current/10' : '' }}"></i>
                Pengguna
            </a>

            <!-- History -->
            <a href="{{ route('admin.history.index') }}" class="flex flex-col items-center justify-center text-[10px] font-bold transition-colors {{ request()->routeIs('admin.history.*') ? 'text-[#001B48]' : 'text-gray-400 hover:text-gray-600' }}">
                <i data-lucide="file-clock" class="w-6 h-6 mb-1 {{ request()->routeIs('admin.history.*') ? 'fill-current/10' : '' }}"></i>
                Riwayat
            </a>

            <!-- Menu Lain (Master Data) -->
            <div x-data="{ open: false }" class="relative flex flex-col items-center justify-center">
                <button @click="open = !open" class="flex flex-col items-center justify-center text-[10px] font-bold text-gray-400 hover:text-gray-600 focus:outline-none w-full h-full">
                    <i data-lucide="menu" class="w-6 h-6 mb-1"></i>
                    Menu
                </button>

                <!-- Dropup Menu -->
                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     class="absolute bottom-20 right-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                    
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase">Master Data</span>
                    </div>
                    
                    <a href="{{ route('admin.risk-levels.index') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#001B48]">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 mr-3 text-blue-500"></i> Tingkat Risiko
                    </a>
                    <a href="{{ route('admin.risk-factors.index') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#001B48]">
                        <i data-lucide="activity" class="w-4 h-4 mr-3 text-orange-500"></i> Faktor Risiko
                    </a>
                    <a href="{{ route('admin.rules.index') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#001B48]">
                        <i data-lucide="git-merge" class="w-4 h-4 mr-3 text-green-500"></i> Aturan
                    </a>
                </div>
            </div>

        </div>
    </nav>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>