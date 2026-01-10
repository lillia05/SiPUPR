@extends('layouts.funding')

@section('title', 'Dashboard Funding')

@section('content')
    <div class="relative bg-gradient-to-r from-bsi-teal to-teal-600 rounded-2xl p-8 mb-10 shadow-lg overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-40 h-40 rounded-full bg-bsi-orange opacity-20 blur-xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
            <div class="text-white mb-4 md:mb-0">
                <h1 class="text-3xl font-heading font-bold">Selamat Datang, {{ auth()->user()->name ?? auth()->user()->username }}!</h1>
                <p class="mt-2 text-teal-100 text-sm md:text-base">Siap melayani nasabah hari ini? Berikut ringkasan aktivitas distribusi buku tabungan.</p>
            </div>
            <div class="text-white text-right">
                <p class="text-xs font-medium uppercase tracking-wider opacity-80">Tanggal Hari Ini</p>
                <p class="text-xl font-bold font-heading">{{ date('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gray-600"></div> 
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Nasabah</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalNasabah }}</p>
                    <p class="text-xs text-gray-400 mt-2">Data bulan ini</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg text-gray-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-400"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Menunggu Cetak</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingCount }}</p>
                    <p class="text-xs text-yellow-600 mt-2 bg-yellow-50 inline-block px-2 py-1 rounded">Menunggu Cetak</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg text-yellow-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Siap Diserahkan</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $readyCount }}</p>
                    <p class="text-xs text-blue-600 mt-2 bg-blue-50 inline-block px-2 py-1 rounded">Siap Diserahkan</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-bsi-teal"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sudah Diserahkan</p>
                    <p class="text-3xl font-bold text-bsi-teal mt-2">{{ $doneCount }}</p>
                    <p class="text-xs text-teal-700 mt-2 bg-teal-50 inline-block px-2 py-1 rounded">Selesai</p>
                </div>
                <div class="p-3 bg-teal-50 rounded-lg text-bsi-teal">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-heading font-semibold text-gray-800">Aktivitas Terbaru</h3>
            <a href="{{ route('funding.tracking.index') }}" class="text-sm font-medium text-bsi-teal hover:text-teal-700 transition">Lihat Selengkapnya</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Nasabah</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($antreanTerbaru as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-bsi-teal flex items-center justify-center text-xs font-bold text-white uppercase">
                                    {{ substr($item->nasabah->user->name ?? 'N', 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->nasabah->user->username ?? 'Nasabah' }}</div>
                                    <div class="text-xs text-gray-500">NIK: {{ $item->nasabah->nik_ktp }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->jenis_produk }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->status == 'done' ? 'bg-teal-100 text-teal-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">Belum ada aktivitas pendaftaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection