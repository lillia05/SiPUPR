<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiFunding BSI') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            bsi: {
                                teal: '#00A39D',
                                dark: '#008C87',
                                orange: '#F7941D',
                                gold: '#C4A006'
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50/50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-gray-50 to-gray-100">
            
            <div class="mb-8 transform hover:scale-105 transition duration-300">
                <a href="/">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" class="h-16 w-auto drop-shadow-sm" alt="Logo BSI" />
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl shadow-gray-200/50 rounded-2xl border border-gray-100 relative overflow-hidden">
                
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-bsi-teal/5 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-bsi-orange/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400 font-medium">
                    &copy; {{ date('Y') }} Bank Syariah Indonesia KC Diponegoro.<br>Dilindungi Undang-Undang.
                </p>
            </div>
        </div>
    </body>
</html>