@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
@endphp

@extends('layouts.cabang')

@section('title', 'Detail Penyaluran - SiPUPR')

@section('content')

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-6 flex flex-col gap-4">
            <div>
                <a href="{{ route($prefix . '.tracking.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:text-bsi-teal hover:border-bsi-teal hover:bg-teal-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
            
            @if(session('error'))
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative mb-8">
            <div class="bg-teal-50 border-b border-teal-100 px-6 py-5 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="h-12 w-12 bg-bsi-teal text-white rounded-full flex items-center justify-center shadow-md mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $penerima->batch->nama_batch ?? 'Batch Tidak Diketahui' }}
                        </h2>
                        <p class="text-sm text-teal-600 font-medium">
                           Progres: {{ $penerima->progress_terkini }}
                        </p>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Update Terakhir</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penerima->updated_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-bsi-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Penerima Bantuan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Nama Lengkap</p>
                        <p class="text-lg font-semibold text-gray-800 mt-1">{{ $penerima->nama_pb ?? $penerima->nama_penerima }}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Wilayah (Desa/Kec/Kab)</p>
                        <p class="text-lg text-gray-800 mt-1">
                            {{ $penerima->desa }}, {{ $penerima->kecamatan }} <br>
                            <span class="text-sm text-gray-500">{{ $penerima->kabupaten }}</span>
                        </p>
                    </div>
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Nomor Rekening</p>
                        <div class="flex items-center mt-1">
                            <p class="text-xl font-bold text-bsi-teal font-mono">{{ $penerima->nomor_rekening ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Deliniasi</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-50 text-bsi-teal mt-1">
                            {{ $penerima->deliniasi }}
                        </span>
                    </div>
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Total Alokasi Bantuan</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">Rp {{ number_format($penerima->total_alokasi_bantuan, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-xs text-gray-400 uppercase font-medium">Sisa Saldo (Belum Cair)</p>
                        <p class="text-lg font-bold text-orange-500 mt-1">Rp {{ number_format($penerima->sisa_saldo, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION KONTROL TAHAPAN --}}
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center px-2">
            <svg class="w-5 h-5 mr-2 text-bsi-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Update Tahapan Penyaluran
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach([1, 2, 3] as $tahap)
                @php 
                    $dataTahap = $penerima->tahapan->where('tahap_ke', $tahap)->first();
                    $isDone = $dataTahap && $dataTahap->status == 'DONE';
                    $defaultNominal = ($tahap == 1 ? 10000000 : ($tahap == 2 ? 7500000 : 2500000));

                    $isLocked = false;
                    if ($tahap > 1) {
                        $prevTahap = $penerima->tahapan->where('tahap_ke', $tahap - 1)->first();
                        if (!$prevTahap || $prevTahap->status != 'DONE') {
                            $isLocked = true;
                        }
                    }
                @endphp

                <div class="bg-white rounded-2xl shadow-md border 
                            {{ $isLocked ? 'border-gray-100 bg-gray-50 opacity-70' : ($isDone ? 'border-green-400 ring-1 ring-green-400' : 'border-gray-200') }} 
                            overflow-hidden transition-all hover:shadow-lg">
                    
                    {{-- Header Tahap --}}
                    <div class="px-5 py-4 {{ $isLocked ? 'bg-gray-100' : ($isDone ? 'bg-green-50' : 'bg-gray-50') }} 
                                border-b {{ $isDone ? 'border-green-100' : 'border-gray-100' }} flex justify-between items-center">
                        <h4 class="font-bold {{ $isLocked ? 'text-gray-400' : ($isDone ? 'text-green-700' : 'text-gray-700') }}">
                            Tahap {{ $tahap }}
                        </h4>
                        @if($isLocked)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                TERKUNCI
                            </span>
                        @elseif($isDone)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-200 text-green-800">SELESAI</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-500">BELUM</span>
                        @endif
                    </div>

                    <div class="p-5">
                        <form action="{{ route($prefix . '.tracking.update_tahap', $penerima->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tahap_ke" value="{{ $tahap }}">

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nominal (Rp)</label>
                                <input type="number" name="nominal" 
                                    value="{{ $dataTahap ? $dataTahap->nominal : $defaultNominal }}" 
                                    {{ $isLocked ? 'disabled' : '' }}
                                    class="block w-full px-3 py-2 text-sm border-gray-300 rounded-lg 
                                           {{ $isLocked ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'focus:ring-bsi-teal focus:border-bsi-teal bg-gray-50 text-gray-700 font-mono' }}">
                            </div>

                            @if($isLocked)
                                <button type="button" disabled class="w-full py-2 bg-gray-200 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed">
                                    Selesaikan Tahap {{ $tahap - 1 }} 
                                </button>
                            @elseif($isDone)
                                <div class="mb-4 text-xs text-center text-green-600 font-medium">
                                    <p>Tercatat Cair: {{ \Carbon\Carbon::parse($dataTahap->tanggal_transaksi)->format('d M Y') }}</p>
                                </div>
                                <input type="hidden" name="status" value="NOT">
                                <button type="submit" class="w-full py-2 bg-white border border-red-200 text-red-500 rounded-xl text-sm font-bold hover:bg-red-50 transition">
                                    Batalkan Status
                                </button>
                            @else
                                <input type="hidden" name="status" value="DONE">
                                <button type="submit" class="w-full py-2 bg-bsi-teal text-white rounded-xl text-sm font-bold hover:bg-teal-700 transition shadow-md flex justify-center items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Konfirmasi Cair
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection