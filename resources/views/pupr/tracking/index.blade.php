@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
@endphp

@extends('layouts.cabang')

@section('title', 'Tracking Berkas - SiPUPR')

@section('content')

    <div x-data="{ openConfirmModal: false, selectedUrl: '', selectedTahap: '', actionType: '' }">

        <div class="mb-6 space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-heading font-bold text-gray-800">Distribusi Bantuan</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Pantau progres penyaluran bantuan 
                        @if(isset($activeBatchId) && $currentBatch = $batches->find($activeBatchId))
                            <span class="ml-1 px-2 py-0.5 rounded bg-teal-50 text-bsi-teal text-xs font-bold border border-teal-100">
                                {{ $currentBatch->nama_batch }}
                            </span>
                        @else
                            <span class="ml-1 px-2 py-0.5 rounded bg-teal-50 text-bsi-teal text-xs font-bold border border-teal-100">
                                Semua Data
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <form action="{{ route($prefix . '.tracking.index') }}" method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-4">
                <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                
                @foreach(['f_deli', 'f_kab', 'f_kec', 'f_desa', 'f_tahap_1', 'f_tahap_2', 'f_tahap_3'] as $field)
                    @if(request($field)) <input type="hidden" name="{{ $field }}" value="{{ request($field) }}"> @endif
                @endforeach

                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="f_nama" value="{{ request('f_nama') }}" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm" 
                        placeholder="Cari Nama Penerima, No. Rekening, atau Wilayah...">
                </div>
                <button type="submit" class="px-4 py-2 bg-bsi-teal text-white rounded-lg text-sm font-bold hover:bg-teal-700 transition shadow-sm">
                    Cari
                </button>
                @if(request()->hasAny(['f_nama', 'f_deli', 'f_kab', 'f_kec', 'f_desa']))
                    <a href="{{ route($prefix . '.tracking.index', ['batch_id' => request('batch_id')]) }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition border border-gray-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded shadow-sm">{{ session('error') }}</div>
        @endif

        {{-- MODAL KONFIRMASI --}}
        <div x-show="openConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openConfirmModal = false"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                 :class="actionType === 'approve' ? 'bg-green-100' : 'bg-red-100'">
                                <svg x-show="actionType === 'approve'" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <svg x-show="actionType === 'cancel'" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="actionType === 'approve' ? 'Konfirmasi Selesai' : 'Konfirmasi Pembatalan'"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-show="actionType === 'approve'">Apakah Anda yakin menyelesaikan <b>Tahap <span x-text="selectedTahap"></span></b>? Status akan menjadi <b>DONE</b>.</p>
                                    <p class="text-sm text-gray-500" x-show="actionType === 'cancel'">Apakah Anda yakin membatalkan <b>Tahap <span x-text="selectedTahap"></span></b>? Status akan kembali <b>NOT</b>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="selectedUrl" method="POST" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" :value="actionType === 'approve' ? 'DONE' : 'NOT'">
                            <input type="hidden" name="tahap_ke" :value="selectedTahap">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition"
                                :class="actionType === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                                x-text="actionType === 'approve' ? 'Ya, Selesai' : 'Ya, Batalkan'"></button>
                        </form>
                        <button type="button" @click="openConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE SECTION --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto min-h-[400px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-10">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[150px]">Nama Penerima</th>
                            
                            {{-- KOLOM DENGAN FILTER DROPDOWN --}}
                            @php
                                $columns = [
                                    'Deliniasi' => 'f_deli',
                                    'Kabupaten' => 'f_kab', 
                                    'Kecamatan' => 'f_kec', 
                                    'Desa' => 'f_desa'
                                ];
                            @endphp

                            @foreach($columns as $label => $field)
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider group relative" x-data="{ open: false }">
                                    <div class="flex items-center justify-between cursor-pointer hover:text-gray-700" @click="open = !open" @click.away="open = false">
                                        <span>{{ $label }}</span>
                                        <svg class="w-3 h-3 ml-1 text-gray-400" :class="{'text-bsi-teal': '{{ request($field) }}'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    </div>
                                    
                                    {{-- Dropdown Filter Input --}}
                                    <div x-show="open" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-20 p-2" x-cloak>
                                        <form action="{{ route($prefix . '.tracking.index') }}" method="GET">
                                            <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                                            <input type="hidden" name="f_nama" value="{{ request('f_nama') }}">
                                            {{-- Keep other filters --}}
                                            @foreach($columns as $l => $f)
                                                @if($f != $field && request($f)) <input type="hidden" name="{{ $f }}" value="{{ request($f) }}"> @endif
                                            @endforeach

                                            <input type="text" name="{{ $field }}" value="{{ request($field) }}" class="w-full text-xs border-gray-300 rounded focus:ring-bsi-teal focus:border-bsi-teal mb-2" placeholder="Filter {{ $label }}...">
                                            <div class="flex justify-end gap-1">
                                                <button type="submit" class="px-2 py-1 bg-bsi-teal text-white text-xs rounded hover:bg-teal-700">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                            @endforeach

                            {{-- KOLOM TAHAPAN DENGAN FILTER STATUS --}}
                            @foreach([1, 2, 3] as $t)
                                <th class="px-2 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24 relative" x-data="{ open: false }">
                                    <div class="flex items-center justify-center cursor-pointer hover:text-gray-700" @click="open = !open" @click.away="open = false">
                                        <span>Tahap {{ $t }}</span>
                                        <svg class="w-3 h-3 ml-1 text-gray-400" :class="{'text-bsi-teal': '{{ request("f_tahap_$t") }}'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>

                                    {{-- Dropdown Filter Status --}}
                                    <div x-show="open" class="absolute top-full right-0 mt-1 w-32 bg-white rounded-md shadow-lg border border-gray-100 z-20 p-2 text-left" x-cloak>
                                        <form action="{{ route($prefix . '.tracking.index') }}" method="GET">
                                            <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                                            {{-- Keep other filters --}}
                                            <div class="space-y-1">
                                                <button type="submit" name="f_tahap_{{ $t }}" value="" class="block w-full text-left px-2 py-1 text-xs hover:bg-gray-50 rounded {{ !request("f_tahap_$t") ? 'font-bold text-bsi-teal' : '' }}">All</button>
                                                <button type="submit" name="f_tahap_{{ $t }}" value="DONE" class="block w-full text-left px-2 py-1 text-xs hover:bg-gray-50 rounded {{ request("f_tahap_$t") == 'DONE' ? 'font-bold text-green-600' : '' }}">DONE</button>
                                                <button type="submit" name="f_tahap_{{ $t }}" value="NOT" class="block w-full text-left px-2 py-1 text-xs hover:bg-gray-50 rounded {{ request("f_tahap_$t") == 'NOT' ? 'font-bold text-red-600' : '' }}">NOT</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                            @endforeach

                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($penerima as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $penerima->firstItem() + $index }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $item->nama_pb ?? $item->nama_penerima }}</div>
                                @if($item->nomor_rekening)
                                    <div class="text-xs text-gray-500 bg-gray-100 inline-block px-1.5 py-0.5 rounded border border-gray-200 mt-1 font-mono">{{ $item->nomor_rekening }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->deliniasi }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->kabupaten }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->kecamatan }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->desa }}</td>

                            {{-- Status Logic --}}
                            @php
                                $s1 = $item->getStatusTahap(1);
                                $s2 = $item->getStatusTahap(2);
                                $s3 = $item->getStatusTahap(3);
                            @endphp

                            @foreach([1, 2, 3] as $t)
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    @php 
                                        $curr = ($t==1?$s1:($t==2?$s2:$s3));
                                        $canApprove = ($curr == 'NOT' && ($t==1 || ($t==2 && $s1=='DONE') || ($t==3 && $s2=='DONE')));
                                        $canRevert = ($curr == 'DONE' && ($t==3 || ($t==2 && $s3=='NOT') || ($t==1 && $s2=='NOT')));
                                    @endphp

                                    @if($curr == 'DONE')
                                        @if($canRevert)
                                            <button @click="openConfirmModal = true; actionType = 'cancel'; selectedUrl = '{{ route($prefix . '.tracking.update_tahap', $item->id) }}'; selectedTahap = '{{ $t }}'"
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 hover:bg-green-200 transition">DONE</button>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 opacity-70 cursor-not-allowed">DONE</span>
                                        @endif
                                    @elseif($canApprove)
                                        <button @click="openConfirmModal = true; actionType = 'approve'; selectedUrl = '{{ route($prefix . '.tracking.update_tahap', $item->id) }}'; selectedTahap = '{{ $t }}'"
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 transition">NOT</button>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400 border border-gray-200 opacity-60 cursor-not-allowed">NOT</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if(Route::has($prefix . '.tracking.show'))
                                    <a href="{{ route($prefix . '.tracking.show', $item->id) }}" class="text-bsi-teal hover:underline font-bold text-xs">Detail</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500 italic">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                {{ $penerima->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

@endsection