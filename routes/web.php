<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\UserController;       // Import UserController
use App\Http\Controllers\MonitoringController; // Import MonitoringController
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Awal -> Redirect ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================================
// ROUTE AUTH (Login, Register, Logout, dll)
// =========================================================================
require __DIR__.'/auth.php';

// =========================================================================
// AREA LOGIN (HANYA AUTH, TIDAK BUTUH VERIFIED)
// =========================================================================
// Perbaikan: Menghapus middleware 'verified' sesuai permintaan
Route::middleware(['auth'])->group(function () {

    // --- LOGIKA REDIRECT DASHBOARD ---
    // Mengarahkan user ke dashboard sesuai role saat mengakses /dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'pupr' || $user->username === 'pupr') {
            return redirect()->route('pupr.dashboard');
        }
        
        if ($user->role === 'cabang') {
            return redirect()->route('cabang.dashboard');
        }

        // Default redirect jika role tidak dikenali
        return redirect('/');
    })->name('dashboard');


    // ================= GROUP 1: KEMENTRIAN PUPR =================
    Route::middleware(['role:pupr'])->prefix('pupr')->name('pupr.')->group(function () {
        
        // Dashboard PUPR
        // (Anda bisa membuat Controller khusus Dashboard jika ingin data dinamis)
        Route::get('/dashboard', function () {
            return view('pupr.dashboard', [
                // Contoh data dummy (bisa diganti dengan data dari database jika perlu)
                'totalNasabah'   => \App\Models\User::where('role', 'Nasabah')->count(), 
                'pendingCount'   => \App\Models\PengajuanRek::whereIn('status', ['draft', 'process'])->count(),
                'readyCount'     => \App\Models\PengajuanRek::where('status', 'ready')->count(),
                'doneCount'      => \App\Models\PengajuanRek::where('status', 'done')->count(),
                'antreanTerbaru' => [] 
            ]); 
        })->name('dashboard');

        // Manajemen User (Menggunakan UserController)
        Route::resource('users', UserController::class);

        // Jika PUPR juga butuh melihat data nasabah (Read Only/Full Access)
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
    });


    // ================= GROUP 2: CABANG =================
    Route::middleware(['role:cabang'])->prefix('cabang')->name('cabang.')->group(function () {
        
        // Dashboard Cabang
        Route::get('/dashboard', function() {
            return view('cabang.dashboard', [
                // Contoh data ringkas untuk dashboard cabang
                'totalNasabah'   => \App\Models\Nasabah::count(),
                'pendingCount'   => \App\Models\PengajuanRek::whereIn('status', ['draft', 'process'])->count(),
                'readyCount'     => \App\Models\PengajuanRek::where('status', 'ready')->count(),
                'doneCount'      => \App\Models\PengajuanRek::where('status', 'done')->count(),
                'antreanTerbaru' => \App\Models\PengajuanRek::with('nasabah.user')->latest()->take(5)->get()
            ]);
        })->name('dashboard');

        // --- MANAJEMEN NASABAH ---
        // Route Import/Export diletakkan sebelum resource agar tidak tertimpa 'show'
        Route::get('nasabah/export', [NasabahController::class, 'export'])->name('nasabah.export');
        Route::post('nasabah/import', [NasabahController::class, 'import'])->name('nasabah.import');
        
        // Route Resource Nasabah
        Route::resource('nasabah', NasabahController::class);

        // --- TRACKING PENGAJUAN (Menggunakan MonitoringController) ---
        // Menampilkan daftar tracking
        Route::get('/tracking', [MonitoringController::class, 'trackingPage'])->name('tracking.index');
        
        // Update status pengajuan
        Route::put('/tracking/{id}', [MonitoringController::class, 'updateStatus'])->name('tracking.update');
        
        // Cetak PDF (Tanda Terima) per item
        Route::get('/tracking/{id}/print', [MonitoringController::class, 'cetakPdfDetail'])->name('tracking.print');
        
        // Fitur pencarian tracking spesifik (jika ada form search terpisah)
        Route::get('/tracking/search', [MonitoringController::class, 'doTracking'])->name('tracking.search');
    });


    // ================= PROFILE =================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});