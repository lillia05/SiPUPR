@extends('layouts.funding')

@section('title', 'Edit User - SiFunding')

@section('content')

    {{-- HEADER & TOMBOL KEMBALI --}}
    <div class="flex items-center justify-between mb-12">
        <div class="flex-1">
            <a href="{{ route('pupr.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-bsi-teal transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
        <div class="flex-1 text-center">
            <h1 class="text-2xl font-heading font-bold text-gray-800">Edit Akun Staff</h1>
            <p class="text-sm text-gray-500 mt-1 whitespace-nowrap">Perbarui informasi akun pengguna.</p>
        </div>
        <div class="flex-1"></div>
    </div>

    {{-- FORM EDIT --}}
    <form action="{{ route('pupr.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (DATA USER) --}}
            <div class="xl:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    {{-- Hiasan Background --}}
                    <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full"></div>
                    
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mr-3 border border-teal-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </span>
                        Edit Informasi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white" required>
                        </div>

                        {{-- Jabatan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan (Role)</label>
                            <div class="relative">
                                <select name="role" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white appearance-none cursor-pointer">
                                    {{-- Sesuaikan Value dengan Database --}}
                                    <option value="Funding" {{ $user->role == 'Funding' ? 'selected' : '' }}>Funding Officer</option>
                                    <option value="pupr" {{ $user->role == 'pupr' ? 'selected' : '' }}>pupr</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CARD PASSWORD --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mr-3 border border-teal-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        Ubah Password
                    </h2>
                    <p class="text-sm text-gray-500 mb-6 ml-14 -mt-2">Kosongkan jika tidak ingin mengubah password.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white" placeholder="********">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white" placeholder="********">
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN (STATUS & AKSI) --}}
            <div class="space-y-6">
                
                {{-- CARD STATUS (INI YANG DIMINTA) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Status Akun</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Login</label>
                            <div class="relative">
                                {{-- Ubah name jadi 'status' dan value jadi 'active'/'inactive' --}}
                                <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal outline-none transition bg-white appearance-none cursor-pointer">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Nonaktif (Dibekukan)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Jika dinonaktifkan, user tidak akan bisa login ke dalam sistem.</p>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <div class="sticky top-8">
                    <button type="submit" class="w-full bg-teal-500 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-teal-600 transition transform flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>

        </div>
    </form>

@endsection