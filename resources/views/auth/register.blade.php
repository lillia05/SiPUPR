<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SiFunding BSI</title>
    
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
                        Buat Akun Baru
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Bergabunglah untuk mempermudah monitoring data nasabah.
                    </p>
                </div>

                <div class="mt-8">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                Username
                            </label>
                            <div class="mt-1">
                                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="Contoh: budisantoso">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="nama@bankbsi.co.id">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required autocomplete="new-password" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="Minimal 8 karakter">
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password
                            </label>
                            <div class="mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" 
                                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-bsi-teal focus:border-bsi-teal sm:text-sm transition duration-200"
                                    placeholder="Ulangi password di atas">
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-bsi-teal h-4 w-4 text-bsi-teal border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-bsi-teal">
                                    Saya menyetujui 
                                    <a href="#" class="font-bold underline hover:text-bsi-dark">
                                        Syarat & Ketentuan
                                    </a>.
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-bsi-orange hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bsi-orange transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="font-medium text-bsi-teal hover:text-bsi-dark hover:underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-bsi-teal">
            <img class="absolute inset-0 h-full w-full object-cover opacity-50" 
                src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" 
                alt="Meeting Background">
            
            <div class="absolute inset-0 bg-gradient-to-t from-bsi-teal via-transparent to-transparent opacity-90"></div>
            
            <div class="absolute inset-0 flex flex-col items-center justify-center p-20 text-center">
                <div class="z-10 text-white max-w-2xl">
                    <h1 class="text-4xl font-bold mb-6">Bergabung dengan Ekosistem Digital BSI</h1>
                    <p class="text-lg text-teal-50 font-light leading-relaxed">
                        Daftarkan diri Anda untuk mulai mengelola berkas dan memantau distribusi layanan perbankan dengan lebih efisien.
                    </p>
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>