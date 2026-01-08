@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-4 md:mb-6">
        <h1 class="text-2xl font-bold text-[#001B48]">Manajemen Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data pengguna sistem (Admin & Client).</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Hapus judul duplikat di toolbar -->
            
            <div class="flex items-center gap-2 w-full md:w-auto ml-auto">
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="q" placeholder="Cari nama/email..." value="{{ request('q') }}" 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#001B48] focus:ring-[#001B48] text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </form>

                <a href="{{ route('admin.users.create') }}" class="bg-[#001B48] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-900 transition flex items-center whitespace-nowrap">
                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Tambah
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-4 py-4 font-semibold w-16 text-center">No</th>
                        <th class="px-4 py-4 font-semibold text-center">Nama</th>
                        <th class="px-4 py-4 font-semibold text-center">Email</th>
                        <th class="px-4 py-4 font-semibold text-center">Role</th>
                        <th class="px-4 py-4 font-semibold text-center">Terdaftar</th>
                        <th class="px-4 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-4 text-sm font-bold text-[#001B48] text-center">{{ $user->name }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ $user->email }}</td>
                        <td class="px-4 py-4 text-sm text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap text-center">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-4 text-center space-x-2 whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#E3943B] text-white rounded-md hover:bg-orange-600 transition leading-none shadow-sm">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition leading-none shadow-sm">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span class="text-xs">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 md:p-6 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection