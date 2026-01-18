@extends('layouts.cabang')

@section('title', 'Data Nasabah - SiPUPR')

@section('content')
@php
    $prefix = strtolower(auth()->user()->role); 
@endphp

    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Data Nasabah</h1>
            <p class="text-sm text-gray-500 mt-1">Database lengkap nasabah.</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-white border border-bsi-teal text-bsi-teal rounded-lg text-sm font-medium hover:bg-teal-50 shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>

            @if(Route::has($prefix . '.nasabah.import'))
            <form action="#" method="POST" enctype="multipart/form-data" class="inline-block">
                @csrf
                <input type="file" name="file_excel" id="file_excel" class="hidden" onchange="this.form.submit()" accept=".xlsx, .xls, .csv">
                
                <label for="file_excel" class="inline-flex items-center px-4 py-2 bg-bsi-teal text-white rounded-lg text-sm font-medium hover:bg-teal-700 shadow-sm transition cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Excel
                </label>
            </form>
            @endif

            <a href="#" class="inline-flex items-center px-4 py-2 bg-bsi-orange text-white rounded-lg text-sm font-bold shadow-md hover:bg-yellow-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Data Nasabah
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route($prefix . '.nasabah.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div class="md:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition" placeholder="Cari Nama, NIK, atau No Rekening...">
            </div>

            <div class="relative">
                <select name="produk" class="block w-full pl-3 pr-8 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm cursor-pointer">
                    <option value="">- Semua Produk -</option>
                    <option value="Payroll Wadiah" {{ request('produk') == 'Payroll Wadiah' ? 'selected' : '' }}>Payroll Wadiah</option>
                    <option value="Easy Wadiah" {{ request('produk') == 'Easy Wadiah' ? 'selected' : '' }}>Easy Wadiah</option>
                    <option value="Easy Mudharabah" {{ request('produk') == 'Easy Mudharabah' ? 'selected' : '' }}>Easy Mudharabah</option>
                    <option value="Haji" {{ request('produk') == 'Haji' ? 'selected' : '' }}>Tabungan Haji</option>
                    <option value="Tapenas" {{ request('produk') == 'Tapenas' ? 'selected' : '' }}>Tapenas</option>
                </select>
            </div>

            <div class="relative">
                <button type="submit" class="w-full bg-bsi-teal text-white py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition shadow-sm">
                    Terapkan Filter
                </button>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200 w-12">No</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200 min-w-[200px]">Nama Penerima Bantuan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">No Rekening</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Deliniasi</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Kabupaten</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Kecamatan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Desa</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider sticky right-0 bg-gray-100 shadow-sm w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nasabah as $index => $item) 
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-500 border-r">{{ $nasabah->firstItem() + $index }}</td>
                        
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r">
                            {{ $item->user->name ?? $item->user->username }}
                        </td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600 border-r">{{ $item->nik_ktp }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ $item->tempat_lahir }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600 border-r">
                            {{ $item->pengajuan->first()->no_rek ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">
                            {{ $item->pengajuan->first()->jenis_produk ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ $item->no_hp }}</td>
                        
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium sticky right-0 bg-white shadow-sm">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="#" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Lihat Detail"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-gray-500">Data nasabah tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    <p class="text-sm text-gray-700 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $nasabah->firstItem() }}</span> 
                        sampai <span class="font-medium">{{ $nasabah->lastItem() }}</span> 
                        dari <span class="font-medium">{{ $nasabah->total() }}</span> data
                    </p>

                    <form action="{{ route($prefix . '.nasabah.index') }}" method="GET" class="flex items-center">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="produk" value="{{ request('produk') }}">

                        <select name="per_page" class="ml-0 sm:ml-2 border-gray-300 rounded-md text-sm focus:ring-bsi-teal focus:border-bsi-teal py-1.5 pl-2 pr-8 cursor-pointer" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-gray-500 ml-2">baris per halaman</span>
                    </form>
                </div>

                <div class="w-full md:w-auto flex justify-center md:justify-end">
                    {{ $nasabah->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection