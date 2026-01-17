@extends('layouts.funding')

@section('title', 'Manajemen Akun')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-heading">Daftar Pengguna</h1>
            <p class="text-sm text-gray-500">Kelola akun pupr dan funding officer.</p>
        </div>
        <div>
            <a href="{{ route('pupr.users.create') }}" class="inline-flex items-center px-4 py-2 bg-bsi-orange text-white rounded-lg text-sm font-bold shadow-md hover:bg-yellow-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Akun Baru
            </a>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- SEARCH & FILTER --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('pupr.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition" placeholder="Cari Username atau Email...">
                </div>

                <div class="relative">
                    <select name="jabatan" class="block w-full pl-3 pr-8 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm cursor-pointer">
                        <option value="">- Semua Jabatan -</option>
                        <option value="Funding" {{ request('jabatan') == 'Funding' ? 'selected' : '' }}>Funding Officer</option>
                        <option value="Admin" {{ request('jabatan') == 'Admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="relative">
                    <button type="submit" class="w-full bg-bsi-teal text-white py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition shadow-sm">
                        Terapkan Filter
                    </button>
                </div>

            </form>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left w-12">No</th>
                        <th class="px-6 py-4 text-left">Username</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-center">Jabatan</th>
                        {{-- [BARU] Kolom Status --}}
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        
                        {{-- 1. NO --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        {{-- 2. USERNAME --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    @if($user->avatar)
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/'.$user->avatar) }}" alt="">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-bsi-teal flex items-center justify-center text-white font-bold text-xs uppercase">
                                            {{ substr($user->username, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-sm font-bold text-gray-900">{{ $user->username }}</div>
                            </div>
                        </td>

                        {{-- 3. EMAIL --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>

                        {{-- 4. TANGGAL DAFTAR --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- 5. JABATAN --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($user->role === 'Admin')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">Administrator</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Funding Officer</span>
                            @endif
                        </td>

                        {{-- [BARU] 6. STATUS (Sebelum Aksi) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span>
                                    Non-aktif
                                </span>
                            @endif
                        </td>

                        {{-- 7. AKSI --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center items-center space-x-3">
                                
                                {{-- Tombol Lihat --}}
                                <a href="{{ route('pupr.users.show', $user['id'] ?? 1) }}" class="flex items-center justify-center w-7 h-7 bg-blue-50 text-blue-600 rounded-md border border-blue-100 hover:bg-blue-100 transition-all duration-200 shadow-sm" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('pupr.users.edit', $user->id) }}" class="flex items-center justify-center w-7 h-7 bg-amber-50 text-amber-600 rounded-md border border-amber-100 hover:bg-yellow-100 transition-all duration-200 shadow-sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pupr.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Hati-hati, menghapus user dapat mempengaruhi data terkait.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-center w-7 h-7 bg-red-50 text-red-600 rounded-md border border-red-100 hover:bg-red-100 transition-all duration-200 shadow-sm" title="Hapus User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            Belum ada data user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FOOTER TABEL --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data pengguna
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection