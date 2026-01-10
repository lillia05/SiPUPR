<x-guest-layout>
    <div class="px-2 py-2">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Buat Kata Sandi Baru</h2>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                Silakan masukkan kata sandi baru untuk akun Anda.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input id="email" class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-100 cursor-not-allowed focus:outline-none" 
                       type="email" name="email" value="{{ old('email', $request->email) }}" required readonly />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                <input id="password" class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal focus:bg-white focus:outline-none transition duration-200" 
                       type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal focus:bg-white focus:outline-none transition duration-200" 
                       type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-bsi-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bsi-teal transition duration-300 transform hover:-translate-y-0.5">
                    Reset Kata Sandi
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>