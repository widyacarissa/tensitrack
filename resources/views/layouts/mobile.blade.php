<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'TensiTrack Mobile')</title>
    
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-[#001B48] antialiased flex flex-col min-h-screen pb-safe-area-bottom">

    <!-- Mobile Header (Simple & Clean) -->
    <header class="bg-white sticky top-0 z-50 px-4 py-3 shadow-sm flex items-center justify-between h-14">
        @if(request()->routeIs('home'))
            <div class="flex items-center">
                <img src="/logo.png" alt="Logo" class="h-8 w-auto mr-2">
                <span class="text-lg font-bold tracking-tight">Tensi<span class="text-[#E3943B]">Track</span></span>
            </div>
        @else
            <div class="flex items-center w-full relative justify-center">
                <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="absolute left-0 p-2 -ml-2 text-gray-600 hover:text-[#001B48]">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </a>
                <h1 class="font-bold text-base text-[#001B48] truncate max-w-[70%]">@yield('title')</h1>
                
                <!-- Right Action (Optional) -->
                <div class="absolute right-0">
                    @yield('header-action')
                </div>
            </div>
        @endif
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative w-full mx-auto max-w-md bg-gray-50 min-h-[calc(100vh-3.5rem)]">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>