@extends('layouts.cabang')

@section('title', 'Data Penerima Bantuan - SiPUPR')

@section('content')
@php
    $prefix = strtolower(auth()->user()->role); 
@endphp

    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Data Penerima Bantuan</h1>
            <p class="text-sm text-gray-500 mt-1">Database data penerima bantuan PUPR.</p>
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
            
            <div class="md:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition" placeholder="Cari Nama, No Rekening, Desa, atau Kecamatan...">
            </div>

            <div class="relative">
                <a href="{{ route($prefix . '.nasabah.export') }}" class="inline-flex items-center px-12 py-2.5 bg-white border border-bsi-teal text-bsi-teal rounded-lg text-sm font-medium hover:bg-teal-50 shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200 w-12">No</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200 min-w-[200px]">Nama Penerima</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">No Rekening</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Deliniasi</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Kabupaten</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Kecamatan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-r border-gray-200">Desa</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nasabah as $index => $item) 
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-500 border-r">{{ $nasabah->firstItem() + $index }}</td>
                        
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r">
                            {{ $item->nama_pb }}
                        </td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600 border-r">
                            {{ $item->nomor_rekening }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">
                            {{ $item->deliniasi }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ $item->kabupaten }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ $item->kecamatan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 border-r">{{ $item->desa }}</td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p>Data penerima bantuan tidak ditemukan.</p>
                            </div>
                        </td>
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