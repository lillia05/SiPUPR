<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col z-10 min-h-screen">
    <div class="h-20 flex items-center px-6 border-b border-gray-100">
        <img class="h-10 w-auto" src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" alt="Logo BSI">
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
        
        @php
            $user = auth()->user();
            $role = $user->role; // Sekarang sudah 'pupr' atau 'cabang'
            
            // Definisikan variabel default
            $roleLabel = 'User';
            $prefix = 'home';
            $isAdmin = false;

            // Logika Penentuan Role
            if ($role === 'pupr') {
                $roleLabel = 'pupr';
                $prefix = 'pupr';
                $isAdmin = true;
            } elseif ($role === 'cabang') {
                $roleLabel = 'cabang';
                $prefix = 'cabang';
                $isAdmin = false;
            }

            // Generate Link berdasarkan prefix
            $dashboardRoute = route($prefix . '.dashboard');
            $nasabahRoute   = route($prefix . '.nasabah.index');
            $trackingRoute  = route($prefix . '.tracking.index');

            // Cek Status Aktif
            $isDashboardActive = request()->routeIs($prefix . '.dashboard');
            $isNasabahActive   = request()->routeIs($prefix . '.nasabah.*');
            $isTrackingActive  = request()->routeIs($prefix . '.tracking.*');
        @endphp


        {{-- 1. DASHBOARD --}}
        <a href="{{ $dashboardRoute }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all 
           {{ $isDashboardActive ? 'bg-gradient-to-r from-teal-50 to-white text-bsi-teal shadow-sm border-l-4 border-bsi-teal' : 'text-gray-600 hover:bg-gray-50 hover:text-bsi-teal' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        
        
        {{-- 3. DATA NASABAH --}}
        <a href="{{ $nasabahRoute }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors
           {{ $isNasabahActive ? 'bg-gradient-to-r from-teal-50 to-white text-bsi-teal border-l-4 border-bsi-teal' : 'text-gray-600 hover:bg-gray-50 hover:text-bsi-teal' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Data Nasabah
        </a>

        {{-- 4. TRACKING BANTUAN --}}
        <a href="{{ $trackingRoute }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors
           {{ $isTrackingActive ? 'bg-gradient-to-r from-teal-50 to-white text-bsi-teal border-l-4 border-bsi-teal' : 'text-gray-600 hover:bg-gray-50 hover:text-bsi-teal' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Tracking Bantuan
        </a>
    </nav>

    {{-- FOOTER SIDEBAR --}}
    @if($user)
    <div class="border-t border-gray-100 p-4 bg-gray-50">
        <div class="flex items-center">
            
            {{-- AVATAR --}}
            <div class="flex-shrink-0">
                @if($user->avatar && file_exists(storage_path('app/public/' . $user->avatar)))
                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                         alt="Avatar" 
                         class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm">
                @else
                    <div class="h-10 w-10 rounded-full bg-bsi-teal flex items-center justify-center text-white font-bold shadow-sm uppercase">
                        {{ substr($user->name ?? $user->username, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="ml-3 overflow-hidden flex-1">
                <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $user->name }}">
                    {{ $user->name ?? $user->username }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ $roleLabel }} 
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1 rounded-md hover:bg-red-50" title="Keluar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>
    @endif
</aside>