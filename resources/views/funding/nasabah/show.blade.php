@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
    
@endphp

@extends('layouts.funding')

@section('title', 'Detail Nasabah - SiFunding')

@section('content')

    <div class="flex items-center justify-between mb-8">
        
        <div class="flex-1">
            {{-- PERBAIKAN: Route Kembali Dinamis --}}
            <a href="{{ route($prefix . '.nasabah.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-bsi-teal transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="flex-1 text-center">
            <h1 class="text-2xl font-heading font-bold text-gray-800">Detail Data Nasabah</h1>
            <p class="text-sm text-gray-500 mt-1 whitespace-nowrap">Informasi lengkap data diri dan akun nasabah.</p>
        </div>

        <div class="flex-1"></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <div class="xl:col-span-2 space-y-8">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-bsi-teal/10 rounded-bl-full"></div>
                
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-teal-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    Informasi Akun & Produk
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Nama Lengkap (Sesuai KTP)</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $nasabah->user->username ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Alamat Email</p>
                        <p class="text-lg font-medium text-gray-800 mt-1">{{ $nasabah->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Rekening BSI Lama</p>
                        <p class="text-lg font-mono text-gray-800 mt-1">{{ $nasabah->rek_bsi_lama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Jenis Tabungan</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-teal-50 text-bsi-teal mt-1 border border-teal-100">
                            @if($nasabah->pengajuan->isNotEmpty())
                                {{ $nasabah->pengajuan->last()->jenis_produk }}
                            @else
                                Belum ada pengajuan
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-orange-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </span>
                    Biodata Diri
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">NIK KTP</p>
                        <p class="text-lg font-mono text-gray-800 mt-1">{{ $nasabah->nik_ktp }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Nomor NPWP</p>
                        <p class="text-lg font-mono text-gray-800 mt-1">{{ $nasabah->npwp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Nama Ibu Kandung</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->nama_ibu }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Tempat Lahir</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->tempat_lahir }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Tanggal Lahir</p>
                        <p class="text-base font-medium text-gray-800 mt-1">
                            {{ $nasabah->tanggal_lahir ? \Carbon\Carbon::parse($nasabah->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Nomor Handphone (WA)</p>
                        <p class="text-lg font-bold text-bsi-teal mt-1">{{ $nasabah->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Status Pernikahan</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->status_pernikahan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Kode Pos</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->kode_pos }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 uppercase font-semibold">Alamat Domisili</p>
                        <p class="text-base text-gray-800 mt-1 leading-relaxed">
                            {{ $nasabah->alamat }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                    Informasi Pekerjaan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 uppercase font-semibold">Area Kerja</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">
                            {{ $nasabah->pekerjaan->area_kerja ?? '-' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400 uppercase font-semibold">Jabatan</p>
                        <p class="text-base font-medium text-gray-800 mt-1">
                            {{ $nasabah->pekerjaan->jabatan ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-8">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    Kontak Darurat
                </h2>

                <div class="space-y-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Nama Kerabat</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->nama_keluarga_tidak_serumah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">No. HP Kerabat</p>
                        <p class="text-base font-medium text-gray-800 mt-1">{{ $nasabah->no_hp_keluarga_tidak_serumah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Alamat Kerabat</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $nasabah->alamat_keluarga_tidak_serumah ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </span>
                    Dokumen Nasabah
                </h2>

                <div class="space-y-6">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Foto KTP</p>
                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm relative group">
                            @if($nasabah->foto_ktp)
                                <img src="{{ asset('storage/' . $nasabah->foto_ktp) }}" alt="Foto KTP" class="w-full h-auto object-cover transition group-hover:opacity-75">
                                <a href="{{ asset('storage/' . $nasabah->foto_ktp) }}" target="_blank" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30">
                                    <span class="px-3 py-1 bg-white rounded-full text-xs font-bold text-gray-800 shadow-sm cursor-pointer">Lihat Penuh</span>
                                </a>
                            @else
                                <div class="w-full h-32 flex items-center justify-center bg-gray-50 text-gray-400 text-sm">
                                    Tidak ada foto KTP
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Foto NPWP</p>
                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm relative group">
                            @if($nasabah->foto_npwp)
                                <img src="{{ asset('storage/' . $nasabah->foto_npwp) }}" alt="Foto NPWP" class="w-full h-auto object-cover transition group-hover:opacity-75">
                                <a href="{{ asset('storage/' . $nasabah->foto_npwp) }}" target="_blank" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30">
                                    <span class="px-3 py-1 bg-white rounded-full text-xs font-bold text-gray-800 shadow-sm cursor-pointer">Lihat Penuh</span>
                                </a>
                            @else
                                <div class="w-full h-32 flex items-center justify-center bg-gray-50 text-gray-400 text-sm">
                                    Tidak ada foto NPWP
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection