@extends('layouts.funding')

@section('title', 'Dashboard Funding')

@section('content')
    {{-- Header Banner --}}
    <div class="relative bg-gradient-to-r from-bsi-teal to-teal-600 rounded-2xl p-8 mb-10 shadow-lg overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-40 h-40 rounded-full bg-bsi-orange opacity-20 blur-xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
            <div class="text-white mb-4 md:mb-0">
                <h1 class="text-3xl font-heading font-bold">Monitoring Penyaluran Dana BSPS</h1>
                <p class="mt-2 text-teal-100 text-sm md:text-base">Kementrian PKP 2025 - Realisasi per tahapan secara terpisah.</p>
            </div>
            <div class="text-white text-right">
                <p class="text-xs font-medium uppercase tracking-wider opacity-80">Update Terakhir</p>
                <p class="text-xl font-bold font-heading">{{ date('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Statistik Penyaluran (Dipisah Tahap 1, 2, dan 3) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Realisasi Tahap 1 --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-bsi-teal"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase">Tahap 1</p>
                    <p class="text-2xl font-bold text-bsi-teal mt-1">100%</p>
                    <span class="text-[10px] px-2 py-0.5 bg-teal-50 text-teal-700 rounded-full font-bold uppercase">Selesai</span>
                </div>
                <div class="p-2 bg-teal-50 rounded-lg text-bsi-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Realisasi Tahap 2 --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-bsi-teal"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase">Tahap 2</p>
                    <p class="text-2xl font-bold text-bsi-teal mt-1">100%</p>
                    <span class="text-[10px] px-2 py-0.5 bg-teal-50 text-teal-700 rounded-full font-bold uppercase">Selesai</span>
                </div>
                <div class="p-2 bg-teal-50 rounded-lg text-bsi-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Realisasi Tahap 3 --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase">Tahap 3</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">0.91%</p>
                    <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full font-bold uppercase">Proses</span>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>

        {{-- Total Sisa Saldo --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-bsi-orange"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Sisa</p>
                    <p class="text-2xl font-bold text-bsi-orange mt-1">Rp 2.72B</p>
                    <span class="text-[10px] text-gray-400">Seluruh Deliniasi</span>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-bsi-orange">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Monitoring dengan Kolom Tahap 1 & 2 Terpisah --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-heading font-semibold text-gray-800">Detail Monitoring Per Deliniasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 text-center">
                    <tr>
                        <th rowspan="2" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase border-r">Deliniasi</th>
                        <th colspan="2" class="px-6 py-2 text-xs font-bold text-teal-600 uppercase border-b border-r">Realisasi (%)</th>
                        <th colspan="2" class="px-6 py-2 text-xs font-bold text-blue-600 uppercase border-b">Tahap Akhir</th>
                    </tr>
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Tahap 1</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase border-r">Tahap 2</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">T3 (%)</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Sisa Saldo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-center">
                    @php
                        $data = [
                            ['label' => 'PESISIR', 'sub' => 'Wilayah Pesisir', 't1' => '100%', 't2' => '100%', 't3' => '1.13%', 'sisa' => '2,185,000,000'],
                            ['label' => 'PERKOTAAN', 'sub' => 'Wilayah Urban', 't1' => '100%', 't2' => '100%', 't3' => '0.00%', 'sisa' => '450,000,000'],
                            ['label' => 'PENERIMA TAMBAHAN', 'sub' => 'Alokasi Cadangan', 't1' => '100%', 't2' => '100%', 't3' => '0.00%', 'sisa' => '85,000,000'],
                        ];
                    @endphp

                    @foreach($data as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-left border-r">
                            <div class="text-sm font-bold text-gray-900">{{ $row['label'] }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">{{ $row['sub'] }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-teal-600">{{ $row['t1'] }}</td>
                        <td class="px-4 py-4 text-sm font-semibold text-teal-600 border-r">{{ $row['t2'] }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col items-center">
                                <span class="text-sm text-gray-900 font-medium">{{ $row['t3'] }}</span>
                                <div class="w-12 bg-gray-100 rounded-full h-1 mt-1">
                                    <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $row['t3'] }}"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 leading-tight">
                            {{ $row['sisa'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td class="px-6 py-4 text-xs text-gray-900 uppercase border-r">Total Keseluruhan</td>
                        <td class="px-4 py-4 text-sm text-teal-600">100%</td>
                        <td class="px-4 py-4 text-sm text-teal-600 border-r">100%</td>
                        <td class="px-4 py-4 text-sm text-blue-600">0.91%</td>
                        <td class="px-6 py-4 text-right text-sm text-bsi-orange">2,720,000,000</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection