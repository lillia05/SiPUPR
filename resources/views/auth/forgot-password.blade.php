<x-guest-layout>
    <div class="px-2 py-2">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Lupa Kata Sandi?</h2>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Terdaftar</label>
                <input id="email" 
                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 
                              focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal focus:bg-white focus:outline-none 
                              transition duration-200 sm:text-sm placeholder-gray-400" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required autofocus 
                       placeholder="contoh: nama@bsi.co.id" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-bsi-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bsi-teal transition duration-300 transform hover:-translate-y-0.5">
                    Kirim Tautan Reset
                </button>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-bsi-teal hover:text-bsi-dark hover:underline transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Halaman Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>