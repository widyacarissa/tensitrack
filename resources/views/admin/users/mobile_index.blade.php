@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')

<div class="flex flex-col h-full">
    
    <!-- Header + Search -->
    <div class="bg-white p-4 border-b border-gray-100 sticky top-0 z-10">
        <div class="flex justify-between items-center mb-4">
            <h1 class="font-bold text-[#001B48] text-lg">Pengguna</h1>
            <a href="{{ route('admin.users.create') }}" class="w-8 h-8 bg-[#001B48] rounded-full flex items-center justify-center text-white shadow-md hover:bg-blue-900 transition">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </a>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..." 
                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#001B48]/20">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5"></i>
        </form>
    </div>

    <!-- Content List -->
    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        @forelse($users as $user)
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <!-- Avatar Initials -->
                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold 
                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                
                <div class="min-w-0">
                    <h3 class="font-bold text-[#001B48] text-sm truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    <div class="flex items-center mt-1 gap-2">
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                            {{ $user->role }}
                        </span>
                        <span class="text-[10px] text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions Dropdown (Simple) -->
            <div class="flex gap-2 ml-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-[#E3943B] hover:text-white transition">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-red-500 hover:text-white transition">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tidak ada data pengguna.</p>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="pt-4 pb-20">
            {{ $users->onEachSide(0)->links('pagination::simple-tailwind') }}
        </div>
    </div>

</div>

@endsection