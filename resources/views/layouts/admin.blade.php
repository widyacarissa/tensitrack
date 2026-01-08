<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - TensiTrack</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        .swal2-popup { font-family: 'Poppins', sans-serif !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false, sidebarMinimized: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="absolute inset-y-0 left-0 z-50 bg-[#001B48] text-white transition-all duration-300 ease-in-out transform md:relative md:translate-x-0 flex flex-col"
               :class="[
                   sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                   sidebarMinimized ? 'w-20' : 'w-64'
               ]">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-white/10 overflow-hidden whitespace-nowrap flex-shrink-0 relative">
                <a href="/" class="flex items-center space-x-2">
                    <img src="/logo.png" alt="Logo" class="h-16 w-auto flex-shrink-0">
                    <span x-show="!sidebarMinimized" class="text-xl font-bold tracking-wide transition-opacity duration-300">Tensi<span class="text-[#E3943B]">Track</span></span>
                </a>
                <!-- Mobile Close Button -->
                <button @click="sidebarOpen = false" class="md:hidden absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white p-2 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Menu Admin -->
            <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto overflow-x-hidden">
                
                <div x-show="!sidebarMinimized" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 transition-opacity duration-300 truncate">Administrator</div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-[#E3943B] bg-white/10' : 'text-gray-300 hover:text-[#E3943B] hover:bg-white/5' }}" title="Dashboard">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Dashboard</span>
                </a>

                <div x-show="!sidebarMinimized" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 transition-opacity duration-300 truncate">Master Data</div>

                <a href="{{ route('admin.risk-levels.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors {{ request()->routeIs('admin.risk-levels.*') ? 'text-[#E3943B] bg-white/10' : '' }}" title="Tingkat Risiko">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Tingkat Risiko</span>
                </a>
                <a href="{{ route('admin.risk-factors.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors {{ request()->routeIs('admin.risk-factors.*') ? 'text-[#E3943B] bg-white/10' : '' }}" title="Faktor Risiko">
                    <i data-lucide="activity" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Faktor Risiko</span>
                </a>
                <a href="{{ route('admin.rules.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors {{ request()->routeIs('admin.rules.*') ? 'text-[#E3943B] bg-white/10' : '' }}" title="Aturan">
                    <i data-lucide="git-merge" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Aturan (Rules)</span>
                </a>

                <div x-show="!sidebarMinimized" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 transition-opacity duration-300 truncate">Manajemen</div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-[#E3943B] bg-white/10' : '' }}" title="Pengguna">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Pengguna</span>
                </a>
                <a href="{{ route('admin.history.index') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors {{ request()->routeIs('admin.history.*') ? 'text-[#E3943B] bg-white/10' : '' }}" title="Laporan Riwayat">
                    <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Laporan Riwayat</span>
                </a>

                <!-- Kembali ke Beranda -->
                <a href="{{ route('home') }}" class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:text-[#E3943B] hover:bg-white/5 transition-colors mt-2 border-t border-white/5" title="Beranda">
                    <i data-lucide="home" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarMinimized" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Beranda</span>
                </a>

            </nav>

            <!-- Logout (Fixed Bottom) -->
            <div class="p-4 border-t border-white/10 mt-auto bg-[#00153a]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md" title="Keluar">
                        <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0" :class="!sidebarMinimized ? 'mr-2' : ''"></i>
                        <span x-show="!sidebarMinimized" class="transition-opacity duration-300 whitespace-nowrap">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            <!-- Topbar -->
            <header class="flex items-center justify-between px-4 py-3 md:px-6 md:py-4 bg-white shadow-sm z-40">
                <div class="flex items-center gap-4">
                    <!-- Toggle Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <button @click="sidebarMinimized = !sidebarMinimized" class="hidden md:block text-gray-500 hover:text-[#001B48] focus:outline-none transition">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <!-- Title & Breadcrumb -->
                    <div>
                        <nav class="text-sm text-gray-500">
                            <ol class="list-none p-0 inline-flex items-center font-medium">
                                <li class="flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#001B48] transition-colors">Admin</a>
                                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-gray-400"></i>
                                </li>
                                <li class="text-[#001B48] font-bold">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-[#001B48]">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">Administrator</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-[#E3943B] flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>
        </div>
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden" style="display: none;" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
    </div>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        function showToast(icon, title, text = '') {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }
        
        // Check for Flashdata Success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#001B48',
                timer: 3000
            });
        @endif

        // Check for Flashdata Error
        @if (session('error'))
            showToast('error', '{{ session('error') }}');
        @endif
        
        // Check for Validation Errors (Array)
        @if ($errors->any())
            showToast('error', 'Terjadi kesalahan validasi', 'Silakan periksa kembali inputan Anda.');
        @endif

        // Global Delete Confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[method="POST"]');
            deleteForms.forEach(form => {
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput && methodInput.value === 'DELETE') {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>