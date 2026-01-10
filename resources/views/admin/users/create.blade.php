@extends('layouts.funding')

@section('title', 'Tambah User Baru - SiFunding')

@section('content')

    {{-- HEADER & TOMBOL KEMBALI --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex-1">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-bsi-teal transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
        <div class="flex-1 text-center">
            <h1 class="text-2xl font-heading font-bold text-gray-800">Tambah Akun Staff</h1>
            <p class="text-sm text-gray-500 mt-1 whitespace-nowrap">Buat akun baru untuk akses sistem.</p>
        </div>
        <div class="flex-1"></div>
    </div>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
            <h3 class="font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Terjadi Kesalahan:
            </h3>
            <ul class="list-disc list-inside ml-7 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT --}}
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (INPUT DATA) --}}
            <div class="xl:col-span-2 space-y-8">
                
                {{-- CARD 1: INFORMASI DASAR --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-bsi-teal/10 rounded-bl-full"></div>
                    
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-teal-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        Informasi Pengguna
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-gray-50 focus:bg-white" placeholder="username_login" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-gray-50 focus:bg-white" placeholder="email@bsi.co.id" required>
                        </div>

                        {{-- Role / Jabatan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan (Role)</label>
                            <div class="relative">
                                <select name="role" class="..." required>
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    <option value="Funding" {{ old('role') == 'Funding' ? 'selected' : '' }}>Funding Officer</option>
                                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 ml-1">*Administrator memiliki akses ke manajemen user dan monitoring.</p>
                        </div>

                    </div>
                </div>

                {{-- CARD 2: KEAMANAN --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-teal-50 text-bsi-teal flex items-center justify-center mr-3 border border-orange-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        Keamanan Akun
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-gray-50 focus:bg-white" placeholder="********" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-gray-50 focus:bg-white" placeholder="********" required>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN (TOMBOL AKSI) --}}
            <div class="sticky top-8 h-fit">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                    <h3 class="font-bold text-gray-800 mb-2">Konfirmasi</h3>
                    <p class="text-sm text-gray-500 mb-6">Pastikan data yang dimasukkan sudah sesuai sebelum menyimpan.</p>
                    
                    <button type="submit" class="w-full bg-bsi-teal text-white font-bold py-4 rounded-xl shadow-lg hover:bg-teal-700 transition transform hover:-translate-y-1 flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Akun Baru
                    </button>
                </div>
            </div>

        </div>
    </form>

@endsection