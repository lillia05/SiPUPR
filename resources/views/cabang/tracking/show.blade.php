@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
    
@endphp

@extends('layouts.funding')

@section('title', 'Detail Tracking - SiFunding')

@section('content')

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ route($prefix . '.tracking.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:text-bsi-teal hover:border-bsi-teal hover:bg-teal-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
            
            <div class="bg-teal-50 border-b border-teal-100 px-6 py-5 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="h-12 w-12 bg-bsi-teal text-white rounded-full flex items-center justify-center shadow-md mr-4">
                        @if($pengajuan->status == 'done')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            @if($pengajuan->status == 'done') Buku Tabungan Diserahkan
                            @elseif($pengajuan->status == 'ready') Buku Tabungan Siap Diserahkan
                            @elseif($pengajuan->status == 'process') Sedang Dalam Proses Cetak
                            @else Berkas Menunggu Verifikasi
                            @endif
                        </h2>
                        <p class="text-sm text-teal-600 font-medium">
                            @if($pengajuan->status == 'done') Proses tracking telah selesai sepenuhnya.
                            @else Berkas sedang diproses oleh petugas.
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Update Terakhir</p>
                    <p class="text-lg font-bold text-gray-800">{{ $pengajuan->updated_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-bsi-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Informasi Rekening Nasabah
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Nama Nasabah</p>
                        <p class="text-lg font-semibold text-gray-800 mt-1">{{ $pengajuan->nasabah->user->username }}</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">NIK (Nomor Induk Kependudukan)</p>
                        <p class="text-lg font-mono text-gray-800 mt-1 bg-gray-50 inline-block px-2 rounded">{{ $pengajuan->nasabah->nik_ktp }}</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Nomor Rekening</p>
                        <div class="flex items-center mt-1">
                            <p class="text-xl font-bold text-bsi-teal font-mono">{{ $pengajuan->no_rek ?? 'Belum Terbit' }}</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Jenis Produk</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-50 text-bsi-teal mt-1">
                            {{ $pengajuan->jenis_produk }}
                        </span>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Nomor Telepon</p>
                        <p class="text-lg font-semibold text-gray-800 mt-1">{{ $pengajuan->nasabah->no_hp }}</p>
                    </div>

                </div>

                @if(Route::has($prefix . '.tracking.print.detail'))
                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                        <a href="{{ route($prefix . '.tracking.print.detail', $pengajuan->id) }}" target="_blank" 
                        class="inline-flex items-center px-4 py-2 bg-bsi-teal border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150 ml-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Tanda Terima
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection