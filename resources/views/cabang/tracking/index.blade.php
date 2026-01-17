@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
@endphp

@extends('layouts.cabang')

@section('title', 'Tracking Berkas - SiPUPR')

@section('content')

    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Distribusi Tabungan</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau progres pencetakan dan distribusi buku tabungan.</p>
        </div>
        
        <div class="flex gap-3">
            @if(Route::has($prefix . '.tracking.print'))
                <a href="{{ route($prefix . '.tracking.print') }}" class="inline-flex items-center px-4 py-2 bg-bsi-teal text-white rounded-lg text-sm font-bold shadow-md hover:bg-teal-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Tanda Terima
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route($prefix . '.tracking.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal transition" placeholder="Cari Nama Nasabah / NIK...">
            </div>
            <select name="status" class="block w-full md:w-48 pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal bg-white text-gray-700">
                <option value="">Semua Progress</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Menunggu Cetak</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diserahkan</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-bsi-teal text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nasabah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jenis Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Masuk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Progres</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Update Terakhir</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengajuans as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pengajuans->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->nasabah->user->username ?? 'Nasabah' }}</div>
                            <div class="text-xs text-gray-500">NIK: {{ $item->nasabah->nik_ktp }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->jenis_produk }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                            <div class="flex items-center space-x-1">
                                <div class="w-8 h-2 rounded-full bg-bsi-teal" title="Input"></div>
                                <div class="w-8 h-2 rounded-full {{ in_array($item->status, ['process', 'ready', 'done']) ? 'bg-yellow-400' : 'bg-gray-200' }}" title="Proses Cetak"></div>
                                <div class="w-8 h-2 rounded-full {{ in_array($item->status, ['ready', 'done']) ? 'bg-blue-500' : 'bg-gray-200' }}" title="Siap Serah"></div>
                                <div class="w-8 h-2 rounded-full {{ $item->status == 'done' ? 'bg-bsi-teal' : 'bg-gray-200' }}" title="Selesai"></div>
                            </div>
                            <span class="text-xs font-medium mt-1 block">
                                @if($item->status == 'draft') <span class="text-gray-400">Menunggu Verifikasi</span>
                                @elseif($item->status == 'process') <span class="text-yellow-600">Menunggu Cetak</span>
                                @elseif($item->status == 'ready') <span class="text-blue-600">Siap Diserahkan</span>
                                @else <span class="text-bsi-teal">Selesai</span>
                                @endif
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $item->updated_at->diffForHumans() }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if(Route::has($prefix . '.updateStatus'))
                                @if($item->status == 'draft' || $item->status == 'process')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-printing-{{ $item->id }}')"
                                        class="text-blue-600 border border-blue-200 bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition text-xs font-bold">
                                        Update Progress
                                    </button>
                                @elseif($item->status == 'ready')
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-handover-{{ $item->id }}')"
                                        class="text-white bg-bsi-teal px-4 py-1.5 rounded-full text-xs font-bold hover:bg-teal-700 transition shadow-sm">
                                        Serahkan
                                    </button>
                                @elseif($item->status == 'done' && Route::has($prefix . '.tracking.show'))
                                    <a href="{{ route($prefix . '.tracking.show', ['search' => $item->nasabah->nik_ktp]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-bsi-teal transition shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Detail
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>

                    @if(Route::has($prefix . '.updateStatus'))
                        <x-modal name="confirm-printing-{{ $item->id }}" focusable maxWidth="sm">
                            <div class="p-6">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-blue-500 rounded-full mb-5 shadow-lg border-4 border-blue-50">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </div>
                                
                                <h2 class="text-lg font-bold text-center text-gray-900 mb-2">
                                    Konfirmasi {{ $item->status == 'draft' ? 'Proses' : 'Cetak' }}
                                </h2>

                                <p class="text-center text-gray-500 text-sm mb-6 leading-relaxed">
                                    Ubah status menjadi <b>{{ $item->status == 'draft' ? 'Menunggu Cetak' : 'Siap Diserahkan' }}</b>?<br>
                                    @if($item->status == 'process')
                                        Silakan input nomor rekening yang telah terbit.
                                    @else
                                        Pastikan data berkas nasabah sudah valid.
                                    @endif
                                </p>

                                <form method="POST" action="{{ route($prefix . '.updateStatus', $item->id) }}"> 
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $item->status == 'draft' ? 'process' : 'ready' }}">

                                    @if($item->status == 'process')
                                        <div class="mb-6 text-left bg-gray-50 p-3 rounded-xl border border-gray-200">
                                            <label for="no_rek" class="block text-xs font-bold text-gray-700 uppercase mb-1 ml-1">
                                                Nomor Rekening Baru <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="no_rek" id="no_rek" required 
                                                placeholder="Masukkan No. Rek..."
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm py-2 px-3 transition">
                                        </div>
                                    @endif
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" x-on:click="$dispatch('close')" class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 transition">
                                            Ya, Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>

                        <x-modal name="confirm-handover-{{ $item->id }}" focusable maxWidth="sm">
                            <div class="p-6">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-bsi-teal rounded-full mb-5 shadow-lg border-4 border-teal-50">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                
                                <h2 class="text-lg font-bold text-center text-gray-900 mb-2">
                                    Konfirmasi Serah Terima
                                </h2>

                                <p class="text-center text-gray-500 text-sm mb-6 leading-relaxed">
                                    Ubah status menjadi <b>Selesai</b>?<br>
                                    Pastikan nasabah sudah menerima buku tabungan.
                                </p>

                                <form method="POST" action="{{ route($prefix . '.updateStatus', $item->id) }}"> 
                                    @csrf
                                    <input type="hidden" name="status" value="done">

                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" x-on:click="$dispatch('close')" class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit" class="w-full px-4 py-2.5 bg-bsi-teal text-white font-bold rounded-xl shadow-md hover:bg-teal-700 transition">
                                            Ya, Selesai
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>
                    @endif

                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    <p class="text-sm text-gray-700 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $pengajuans->firstItem() }}</span> 
                        sampai <span class="font-medium">{{ $pengajuans->lastItem() }}</span> 
                        dari <span class="font-medium">{{ $pengajuans->total() }}</span> data
                    </p>

                    <form action="{{ route($prefix . '.tracking.index') }}" method="GET" class="flex items-center">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

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
                    {{ $pengajuans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection