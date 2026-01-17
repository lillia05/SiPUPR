<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NasabahRegistrationController;
// 1. TAMBAHKAN IMPORT INI AGAR CONTROLLER TERBACA
use App\Http\Controllers\NasabahController; 
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
// ROUTE AUTH
// =========================================================================
require __DIR__.'/auth.php';

// =========================================================================
// AREA LOGIN (AUTH & VERIFIED)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- LOGIKA REDIRECT DASHBOARD ---
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'pupr') {
            return redirect()->route('pupr.dashboard');
        }
        
        if ($user->role === 'cabang') {
            return redirect()->route('cabang.dashboard');
        }

        return redirect('/');
    })->name('dashboard');


    // ================= GROUP 1: KEMENTRIAN PUPR =================
    Route::middleware(['role:pupr'])->prefix('pupr')->name('pupr.')->group(function () {
        
        // Dashboard PUPR
        Route::get('/dashboard', function () {
            return view('pupr.dashboard', [
                'totalNasabah'   => 150, 
                'pendingCount'   => 12,
                'readyCount'     => 5,
                'doneCount'      => 133,
                'antreanTerbaru' => [] 
            ]); 
        })->name('dashboard');

        // Manajemen User
        Route::get('/users', function() {
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('pupr.users.index', compact('users')); 
        })->name('users.index');
        
        // Jika PUPR juga butuh melihat data nasabah, arahkan ke Controller juga
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
    });


    // ================= GROUP 2: CABANG =================
    Route::middleware(['role:cabang'])->prefix('cabang')->name('cabang.')->group(function () {
        
        // Dashboard Cabang
        Route::get('/dashboard', function() {
            return view('cabang.dashboard', [
                'totalNasabah'   => 50,
                'pendingCount'   => 5,
                'readyCount'     => 2,
                'doneCount'      => 43,
                'antreanTerbaru' => []
            ]);
        })->name('dashboard');

        // --- [PERBAIKAN DISINI] ---
        // Menggunakan Controller, bukan function dummy lagi.
        
        // 1. Route Khusus (Import/Export) ditaruh SEBELUM resource
        Route::get('nasabah/export', [NasabahController::class, 'export'])->name('nasabah.export');
        Route::post('nasabah/import', [NasabahController::class, 'import'])->name('nasabah.import');

        // 2. Route Resource (Otomatis membuat route index, create, store, edit, update, destroy)
        // Ini akan membuat route bernama: cabang.nasabah.index, cabang.nasabah.store, dst.
        Route::resource('nasabah', NasabahController::class);

        // Tracking (Masih Dummy, nanti buatkan controllernya jika sudah ada)
        Route::get('/tracking', function() { 
            return view('cabang.tracking.index', ['pengajuans' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10)]); 
        })->name('tracking.index');
    });


    // ================= PROFILE (Bisa diakses keduanya) =================
    Route::middleware(['role:pupr,cabang'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});