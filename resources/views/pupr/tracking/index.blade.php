@php
    $userRole = auth()->user()->role;
    $prefix = strtolower($userRole); 
    
    $uniqueDeliniasi = \App\Models\PenerimaBantuan::distinct()->pluck('deliniasi')->filter();
    $uniqueKabupaten = \App\Models\PenerimaBantuan::distinct()->pluck('kabupaten')->filter();
    $uniqueKecamatan = \App\Models\PenerimaBantuan::distinct()->pluck('kecamatan')->filter();
    $uniqueDesa      = \App\Models\PenerimaBantuan::distinct()->pluck('desa')->filter();
@endphp

@extends('layouts.cabang') 

@section('title', 'Tracking Bantuan - SiPUPR')

@section('content')

    <div x-data="{ }">

        <div class="mb-6 space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-heading font-bold text-gray-800">Monitoring Distribusi</h1>
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
                        placeholder="Cari Nama Penerima, No. Rekening...">
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto min-h-[400px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-10">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[150px]">Nama Penerima</th>
                            
                            @php
                                $columns = [
                                    'Deliniasi' => ['field' => 'f_deli', 'options' => $uniqueDeliniasi],
                                    'Kabupaten' => ['field' => 'f_kab',  'options' => $uniqueKabupaten], 
                                    'Kecamatan' => ['field' => 'f_kec',  'options' => $uniqueKecamatan], 
                                    'Desa'      => ['field' => 'f_desa', 'options' => $uniqueDesa]
                                ];
                            @endphp

                            @foreach($columns as $label => $data)
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider group relative" x-data="{ open: false }">
                                    <div class="flex items-center justify-between cursor-pointer hover:text-gray-700" @click="open = !open" @click.away="open = false">
                                        <span class="{{ request($data['field']) ? 'text-bsi-teal font-bold' : '' }}">{{ $label }}</span>
                                        <svg class="w-3 h-3 ml-1 text-gray-400" :class="{'text-bsi-teal': '{{ request($data['field']) }}'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    </div>
                                    
                                    <div x-show="open" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg border border-gray-100 z-20 p-2" x-cloak>
                                        <form action="{{ route($prefix . '.tracking.index') }}" method="GET">
                                            <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                                            <input type="hidden" name="f_nama" value="{{ request('f_nama') }}">
                                            @foreach($columns as $l => $d)
                                                @if($d['field'] != $data['field'] && request($d['field'])) 
                                                    <input type="hidden" name="{{ $d['field'] }}" value="{{ request($d['field']) }}"> 
                                                @endif
                                            @endforeach

                                            <div class="max-h-48 overflow-y-auto space-y-1">
                                                <button type="submit" name="{{ $data['field'] }}" value="" class="block w-full text-left px-2 py-1.5 text-xs rounded hover:bg-gray-50 {{ !request($data['field']) ? 'font-bold text-bsi-teal bg-teal-50' : 'text-gray-600' }}">All {{ $label }}</button>
                                                @foreach($data['options'] as $option)
                                                    <button type="submit" name="{{ $data['field'] }}" value="{{ $option }}" class="block w-full text-left px-2 py-1.5 text-xs rounded hover:bg-gray-50 truncate {{ request($data['field']) == $option ? 'font-bold text-bsi-teal bg-teal-50' : 'text-gray-600' }}" title="{{ $option }}">{{ $option }}</button>
                                                @endforeach
                                            </div>
                                        </form>
                                    </div>
                                </th>
                            @endforeach

                            @foreach([1, 2, 3] as $t)
                                <th class="px-2 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24 relative" x-data="{ open: false }">
                                    <div class="flex items-center justify-center cursor-pointer hover:text-gray-700" @click="open = !open" @click.away="open = false">
                                        <span class="{{ request("f_tahap_$t") ? 'text-bsi-teal font-bold' : '' }}">Tahap {{ $t }}</span>
                                        <svg class="w-3 h-3 ml-1 text-gray-400" :class="{'text-bsi-teal': '{{ request("f_tahap_$t") }}'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div x-show="open" class="absolute top-full right-0 mt-1 w-32 bg-white rounded-md shadow-lg border border-gray-100 z-20 p-2 text-left" x-cloak>
                                        <form action="{{ route($prefix . '.tracking.index') }}" method="GET">
                                            <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                                            <div class="space-y-1">
                                                <button type="submit" name="f_tahap_{{ $t }}" value="" class="block w-full text-left px-2 py-1.5 text-xs hover:bg-gray-50 rounded {{ !request("f_tahap_$t") ? 'font-bold text-bsi-teal bg-teal-50' : 'text-gray-600' }}">All</button>
                                                <button type="submit" name="f_tahap_{{ $t }}" value="DONE" class="block w-full text-left px-2 py-1.5 text-xs hover:bg-gray-50 rounded {{ request("f_tahap_$t") == 'DONE' ? 'font-bold text-green-600 bg-green-50' : 'text-gray-600' }}">DONE</button>
                                                <button type="submit" name="f_tahap_{{ $t }}" value="NOT" class="block w-full text-left px-2 py-1.5 text-xs hover:bg-gray-50 rounded {{ request("f_tahap_$t") == 'NOT' ? 'font-bold text-red-600 bg-red-50' : 'text-gray-600' }}">NOT</button>
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

                            @foreach([1, 2, 3] as $t)
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    @php $status = $item->getStatusTahap($t); @endphp
                                    
                                    @if($status == 'DONE')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 cursor-default">
                                            DONE
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-400 border border-red-200 cursor-default">
                                            NOT
                                        </span>
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