<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiFunding BSI</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bsi: {
                            teal: '#00A39D',    /* Warna Utama BSI */
                            dark: '#008C87',    /* Warna Hover */
                            orange: '#F7941D',  /* Warna Aksen */
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen flex">
        
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white w-full lg:w-[45%]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div class="text-left">
                    <img class="h-16 w-auto mb-6" src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" alt="Logo BSI">
                    
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Selamat Datang
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan masuk untuk mengakses <br>
                        <span class="font-semibold text-bsi-teal">Sistem Monitoring Distribusi Buku Tabungan</span>
                    </p>
                </div>

                <div class="mt-8">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf 

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                Username
                            </label>
                            <div class="mt-1">
                                <input id="username" name="username" type="text" :value="old('username')" required autofocus autocomplete="username" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="Masukkan username Anda">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required autocomplete="current-password" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" 
                                    class="h-4 w-4 text-bsi-teal focus:ring-bsi-teal border-gray-300 rounded cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                    Ingat saya
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-bsi-teal hover:text-bsi-dark">
                                        Lupa kata sandi?
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-bsi-orange hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bsi-orange transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                Masuk ke Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Belum memiliki akun? 
                            <a href="{{ route('register') }}" class="font-medium text-bsi-teal hover:text-bsi-dark hover:underline">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>

                </div>
                
                <div class="mt-10 text-center">
                    <p class="text-xs text-gray-400">
                        &copy; {{ date('Y') }} Bank Syariah Indonesia KC Diponegoro.<br>Dilindungi Undang-Undang.
                    </p>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <img class="absolute inset-0 h-full w-full object-cover opacity-60" 
                src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" 
                alt="Office Background">
            
            <div class="absolute inset-0 bg-gradient-to-br from-bsi-teal to-gray-900 opacity-80 mix-blend-multiply"></div>
            
            <div class="absolute inset-0 flex flex-col items-center justify-center p-20 text-center">
                <div class="z-10 text-white max-w-2xl">
                    <img class="h-12 w-auto mx-auto mb-8 opacity-80" src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" style="filter: brightness(0) invert(1);" alt="Logo Putih">
                    <h1 class="text-4xl font-bold mb-6 leading-tight">Efisiensi & Akurasi dalam Satu Genggaman</h1>
                    <p class="text-lg text-teal-100 font-light leading-relaxed">
                        Sistem Informasi Funding mempermudah tracking berkas nasabah dan distribusi buku tabungan secara real-time.
                    </p>
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>