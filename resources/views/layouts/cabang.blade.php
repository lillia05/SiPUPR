<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiPUPR BSI')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bsi: { teal: '#00A39D', dark: '#008C87', orange: '#F7941D', gold: '#C4A006' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="flex h-screen overflow-hidden">

        @include('components.sidebar')

        <main class="flex-1 overflow-y-auto bg-gray-50/50">
            <div class="md:hidden h-16 bg-white border-b flex items-center px-4 justify-between sticky top-0 z-20">
                <img class="h-6" src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" alt="Logo">
                <button class="text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <div class="max-w-7xl mx-auto px-6 py-8">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>