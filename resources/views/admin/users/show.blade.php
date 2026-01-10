@extends('layouts.funding')

@section('title', 'Detail User - SiFunding')

@section('content')

    {{-- HEADER & TOMBOL KEMBALI --}}
    <div class="flex items-center justify-between mb-12">
        <div class="flex-1">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-bsi-teal transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
        <div class="flex-1 text-center">
            <h1 class="text-2xl font-heading font-bold text-gray-800">Detail Akun Staff</h1>
            <p class="text-sm text-gray-500 mt-1 whitespace-nowrap">Informasi lengkap akun pengguna.</p>
        </div>
        <div class="flex-1 text-right">
            {{-- Tombol Edit Cepat (Opsional) --}}
            {{-- <a href="#" class="text-sm font-bold text-bsi-teal hover:underline">Edit Data Ini</a> --}}
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (DATA UTAMA) --}}
        <div class="xl:col-span-2 space-y-8">
            
            {{-- CARD: INFORMASI PENGGUNA --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-bsi-teal/10 rounded-bl-full"></div>
                
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-3 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    Informasi Pengguna
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-500 mb-2">Username</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-800">
                            {{ $user->username }}
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-500 mb-2">Alamat Email</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    {{-- Jabatan --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-500 mb-2">Jabatan (Role)</label>
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-between">
                            <span class="font-medium text-gray-800">
                                @if($user->role === 'admin')
                                    Administrator (Akses Penuh)
                                @elseif($user->role === 'funding_officer')
                                    Funding Officer (Staff Lapangan)
                                @else
                                    {{ ucfirst($user->role) }}
                                @endif
                            </span>
                            
                            {{-- Badge --}}
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700 border border-red-200">Administrator</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-700 border border-blue-200">Funding Officer</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (INFO TAMBAHAN) --}}
        <div class="space-y-6">
            
            {{-- CARD: STATUS & WAKTU --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Meta Data</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Tanggal Bergabung</p>
                        <p class="text-sm font-medium text-gray-700 mt-1">
                            {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Terakhir Diupdate</p>
                        <p class="text-sm font-medium text-gray-700 mt-1">
                            {{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Status Akun</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection