@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
@endphp

@extends('layouts.cabang')

@section('title', 'Tracking Berkas - SiPUPR')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Distribusi Bantuan</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pantau progres distribusi penyaluran bantuan per Batch.
                @if(request('batch_id'))
                    @php 
                        $currentBatch = \App\Models\Batch::find(request('batch_id'));
                    @endphp
                    @if($currentBatch)
                        <span class="ml-2 px-3 py-1 rounded-full bg-teal-50 text-bsi-teal text-xs font-bold border border-teal-200">
                            {{ $currentBatch->nama_batch }}
                        </span>
                    @endif
                @endif
            </p>
        </div>
        
        <div class="flex gap-3">
            @if(Route::has($prefix . '.tracking.cetak'))
                <a href="{{ route($prefix . '.tracking.cetak') }}" class="inline-flex items-center px-4 py-2 bg-bsi-teal text-white rounded-lg text-sm font-bold shadow-md hover:bg-teal-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Form Filter Bungkus Tabel --}}
        <form action="{{ route($prefix . '.tracking.index') }}" method="GET">
            {{-- Pertahankan batch_id saat filter --}}
            <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        {{-- Baris 1: Judul Kolom --}}
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider min-w-[150px]">Nama Penerima</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Deliniasi</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kabupaten</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kecamatan</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Desa</th>
                            
                            {{-- Kolom Tahapan --}}
                            <th scope="col" class="px-2 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-24">Tahap 1</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-24">Tahap 2</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-24">Tahap 3</th>
                            
                            <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>

                        {{-- Baris 2: Input Filter (Sesuai Request) --}}
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-2 py-2"></td> {{-- No --}}
                            
                            {{-- Filter Nama --}}
                            <td class="px-2 py-2">
                                <input type="text" name="f_nama" value="{{ request('f_nama') }}" 
                                    class="block w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal placeholder-gray-400" 
                                    placeholder="Cari Nama...">
                            </td>

                            {{-- Filter Deliniasi --}}
                            <td class="px-2 py-2">
                                <input type="text" name="f_deli" value="{{ request('f_deli') }}" 
                                    class="block w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal placeholder-gray-400" 
                                    placeholder="Filter...">
                            </td>

                            {{-- Filter Kabupaten --}}
                            <td class="px-2 py-2">
                                <input type="text" name="f_kab" value="{{ request('f_kab') }}" 
                                    class="block w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal placeholder-gray-400" 
                                    placeholder="Filter...">
                            </td>

                            {{-- Filter Kecamatan --}}
                            <td class="px-2 py-2">
                                <input type="text" name="f_kec" value="{{ request('f_kec') }}" 
                                    class="block w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal placeholder-gray-400" 
                                    placeholder="Filter...">
                            </td>

                            {{-- Filter Desa --}}
                            <td class="px-2 py-2">
                                <input type="text" name="f_desa" value="{{ request('f_desa') }}" 
                                    class="block w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal placeholder-gray-400" 
                                    placeholder="Filter...">
                            </td>

                            {{-- Filter Tahap 1 --}}
                            <td class="px-1 py-2">
                                <select name="f_tahap_1" class="block w-full px-1 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal">
                                    <option value="">All</option>
                                    <option value="DONE" {{ request('f_tahap_1') == 'DONE' ? 'selected' : '' }}>DONE</option>
                                    <option value="NOT" {{ request('f_tahap_1') == 'NOT' ? 'selected' : '' }}>NOT</option>
                                </select>
                            </td>

                            {{-- Filter Tahap 2 --}}
                            <td class="px-1 py-2">
                                <select name="f_tahap_2" class="block w-full px-1 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal">
                                    <option value="">All</option>
                                    <option value="DONE" {{ request('f_tahap_2') == 'DONE' ? 'selected' : '' }}>DONE</option>
                                    <option value="NOT" {{ request('f_tahap_2') == 'NOT' ? 'selected' : '' }}>NOT</option>
                                </select>
                            </td>

                            {{-- Filter Tahap 3 --}}
                            <td class="px-1 py-2">
                                <select name="f_tahap_3" class="block w-full px-1 py-1 text-xs border border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal">
                                    <option value="">All</option>
                                    <option value="DONE" {{ request('f_tahap_3') == 'DONE' ? 'selected' : '' }}>DONE</option>
                                    <option value="NOT" {{ request('f_tahap_3') == 'NOT' ? 'selected' : '' }}>NOT</option>
                                </select>
                            </td>

                            {{-- Tombol Filter --}}
                            <td class="px-2 py-2 text-center">
                                <button type="submit" class="p-1.5 bg-gray-100 text-gray-600 rounded-md hover:bg-bsi-teal hover:text-white transition shadow-sm border border-gray-300" title="Terapkan Filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($penerima as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $penerima->firstItem() + $index }}</td>
                            
                            {{-- Nama & Info Akun --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $item->nama_pb ?? $item->nama_penerima }}</div>
                                @if($item->nomor_rekening)
                                    <div class="text-xs text-gray-500 bg-gray-100 inline-block px-1.5 py-0.5 rounded border border-gray-200 mt-1">
                                        {{ $item->nomor_rekening }}
                                    </div>
                                @endif
                            </td>

                            {{-- Wilayah --}}
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->deliniasi }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->kabupaten }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->kecamatan }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->desa }}</td>
                            
                            {{-- Status Tahap 1 --}}
                            <td class="px-2 py-4 whitespace-nowrap text-center">
                                @php $status1 = $item->getStatusTahap(1); @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border 
                                    {{ $status1 == 'DONE' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-100' }}">
                                    {{ $status1 }}
                                </span>
                            </td>

                            {{-- Status Tahap 2 --}}
                            <td class="px-2 py-4 whitespace-nowrap text-center">
                                @php $status2 = $item->getStatusTahap(2); @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border 
                                    {{ $status2 == 'DONE' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-100' }}">
                                    {{ $status2 }}
                                </span>
                            </td>

                            {{-- Status Tahap 3 --}}
                            <td class="px-2 py-4 whitespace-nowrap text-center">
                                @php $status3 = $item->getStatusTahap(3); @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border 
                                    {{ $status3 == 'DONE' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-100' }}">
                                    {{ $status3 }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if(Route::has($prefix . '.tracking.show'))
                                    <a href="{{ route($prefix . '.tracking.show', $item->id) }}" 
                                       class="text-bsi-teal hover:text-teal-800 font-bold text-xs hover:underline">
                                        Detail
                                    </a>
                                @else
                                    <button class="text-gray-400 cursor-not-allowed text-xs" disabled>Detail</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500 italic">
                                Data penerima bantuan tidak ditemukan untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                        <p class="text-sm text-gray-700 text-center sm:text-left">
                            Menampilkan <span class="font-medium">{{ $penerima->firstItem() }}</span> 
                            sampai <span class="font-medium">{{ $penerima->lastItem() }}</span> 
                            dari <span class="font-medium">{{ $penerima->total() }}</span> data
                        </p>

                        <div class="flex items-center">
                            <select name="per_page" class="ml-0 sm:ml-2 border-gray-300 rounded-md text-sm focus:ring-bsi-teal focus:border-bsi-teal py-1.5 pl-2 pr-8 cursor-pointer" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-gray-500 ml-2">baris per halaman</span>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex justify-center md:justify-end">
                        {{-- Gunakan appends untuk mempertahankan query parameter saat pindah halaman --}}
                        {{ $penerima->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

        </form> {{-- Tutup Form Filter --}}
    </div>

@endsection