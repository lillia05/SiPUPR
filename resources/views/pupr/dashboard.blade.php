@extends('layouts.cabang')

@section('title', 'Dashboard PUPR')

@section('content')
    {{-- HEADER --}}
    <div class="relative bg-gradient-to-r from-bsi-teal to-teal-600 rounded-2xl p-8 mb-10 shadow-lg overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-40 h-40 rounded-full bg-bsi-orange opacity-20 blur-xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
            <div class="text-white mb-4 md:mb-0">
                <h1 class="text-3xl font-heading font-bold">Selamat Datang, {{ auth()->user()->name ?? auth()->user()->username }}!</h1>
                <p class="mt-2 text-teal-100 text-sm md:text-base">Anda memiliki akses penuh untuk manajemen akun dan monitoring sistem.</p>
            </div>
            <div class="text-white text-right">
                <p class="text-xs font-medium uppercase tracking-wider opacity-80">Tanggal Hari Ini</p>
                <p class="text-xl font-bold font-heading">{{ date('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        
        {{-- KARTU 1: TOTAL AKUN FUNDING --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Akun Funding</p>
                    
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'Funding')->count() }}</p>
                    
                    <p class="text-xs text-gray-400 mt-2">Staff Aktif</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- KARTU 2: TOTAL NASABAH --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-bsi-orange"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Nasabah</p>
                    {{-- Menggunakan variable dari controller atau query langsung jika variabel belum ada --}}
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalNasabah ?? \App\Models\Nasabah::count() }}</p>
                    <p class="text-xs text-gray-400 mt-2">Data Keseluruhan</p>
                </div>
                <div class="p-3 bg-orange-50 rounded-lg text-bsi-orange">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- KARTU 3: TOTAL BUKU TABUNGAN SELESAI --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-teal-500"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Buku Tabungan Selesai</p>
                    {{-- Menggunakan variable dari controller atau query langsung --}}
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $doneCount ?? \App\Models\PengajuanRek::where('status', 'done')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-2">Telah Didistribusikan</p>
                </div>
                <div class="p-3 bg-teal-50 rounded-lg text-bsi-teal">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL PREVIEW (Tanpa Tombol Aksi) --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-heading font-semibold text-gray-800">Akun Terbaru</h3>
            {{-- Link ke Manajemen Akun untuk aksi lengkap --}}
            <a href="{{ route('pupr.users.index') }}" class="text-sm font-medium text-bsi-teal hover:text-teal-700 transition">Lihat Manajemen Akun</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    
                    @php
                        $latestUsers = \App\Models\User::whereIn('role', ['pupr', 'cabang'])
                                        ->latest()
                                        ->take(5)
                                        ->get();
                    @endphp

                    @forelse($latestUsers as $user)
                    <tr class="hover:bg-gray-50 transition">
                        {{-- 1. No --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        {{-- 2. Username --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $user->username }}</div>
                        </td>

                        {{-- 3. Email --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>

                        {{-- 4. Tanggal Daftar --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y, H:i') }}
                        </td>

                        {{-- 5. Jabatan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($user->role === 'pupr')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">Administrator</span>
                            @elseif($user->role === 'cabang')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Funding Officer</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">{{ $user->role }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">Belum ada akun staff lain.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection