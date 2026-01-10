<x-guest-layout>
    <div class="px-2 py-2">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Email Anda</h2>
            <p class="mt-3 text-sm text-gray-500 leading-relaxed text-justify">
                Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.
            </p>
            <p class="mt-2 text-sm text-gray-500">
                Jika Anda tidak menerima email, kami dengan senang hati akan mengirimkan ulang.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-bsi-teal hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bsi-teal transition duration-300 transform hover:-translate-y-0.5">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>