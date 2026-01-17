<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NasabahRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Awal -> Login
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================================
// ROUTE PUBLIK
// =========================================================================
Route::get('/pengajuan-nasabah', [NasabahRegistrationController::class, 'create'])->name('nasabah.register.create');
Route::post('/pengajuan-nasabah', [NasabahRegistrationController::class, 'store'])->name('nasabah.register.store');

// =========================================================================
// ROUTE AUTH
// =========================================================================
require __DIR__.'/auth.php';

// =========================================================================
// AREA LOGIN (AUTH & VERIFIED)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- LOGIKA REDIRECT DASHBOARD UTAMA ---
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Jika Admin -> ke Dashboard PUPR
        if ($user->role === 'Admin' || $user->username === 'admin') {
            return redirect()->route('pupr.dashboard');
        }
        
        // Jika Funding -> ke Dashboard Cabang
        if ($user->role === 'Funding') {
            return redirect()->route('cabang.dashboard');
        }

        return redirect('/');
    })->name('dashboard');


    // ================= GROUP 1: KEMENTRIAN PUPR (Admin) =================
    // Menggunakan Data Dummy untuk Tampilan Frontend
    Route::middleware(['role:Admin'])->prefix('pupr')->name('pupr.')->group(function () {
        
        // 1. Dashboard PUPR
        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'totalNasabah'   => 150, 
                'pendingCount'   => 12,
                'readyCount'     => 5,
                'doneCount'      => 133,
                'antreanTerbaru' => [] 
            ]); 
        })->name('dashboard');

        // 2. Manajemen User (Dummy)
        Route::get('/users', function() {
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('admin.users.index', compact('users')); 
        })->name('users.index');
        
        // 3. Dummy Nasabah & Tracking (Supaya Sidebar tidak error)
        Route::get('/nasabah', function() { 
            return view('funding.nasabah.index', ['nasabahs' => []]); 
        })->name('nasabah.index');
        
        Route::get('/tracking', function() { 
            return view('funding.tracking.index', ['pengajuans' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10)]); 
        })->name('tracking.index');
    });


    // ================= GROUP 2: CABANG (Funding) =================
    // Menggunakan Data Dummy untuk Tampilan Frontend
    Route::middleware(['role:Funding'])->prefix('cabang')->name('cabang.')->group(function () {
        
        // 1. Dashboard Cabang
        Route::get('/dashboard', function() {
            return view('funding.dashboard', [
                'totalNasabah'   => 50,
                'pendingCount'   => 5,
                'readyCount'     => 2,
                'doneCount'      => 43,
                'antreanTerbaru' => []
            ]);
        })->name('dashboard');

        // 2. Dummy Nasabah
        Route::get('/nasabah', function() { 
            return view('funding.nasabah.index', ['nasabahs' => []]); 
        })->name('nasabah.index');
        
        // 3. Dummy Tracking
        Route::get('/tracking', function() { 
            return view('funding.tracking.index', ['pengajuans' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10)]); 
        })->name('tracking.index');
    });


    // ================= PROFILE (Bisa diakses keduanya) =================
    Route::middleware(['role:Admin,Funding'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});