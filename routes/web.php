<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\UserController;       
use App\Http\Controllers\MonitoringController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    // --- LOGIKA REDIRECT DASHBOARD ---
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'pupr' || $user->username === 'pupr') {
            return redirect()->route('pupr.dashboard');
        }
        if ($user->role === 'cabang') {
            return redirect()->route('cabang.dashboard');
        }
        return redirect('/');
    })->name('dashboard');


    // ================= GROUP 1: KEMENTRIAN PUPR =================
    Route::middleware(['role:pupr'])->prefix('pupr')->name('pupr.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', function () {
            return view('pupr.dashboard', [
                'totalNasabah'   => \App\Models\User::where('role', 'Nasabah')->count(), 
                'pendingCount'   => \App\Models\PengajuanRek::whereIn('status', ['draft', 'process'])->count(),
                'readyCount'     => \App\Models\PengajuanRek::where('status', 'ready')->count(),
                'doneCount'      => \App\Models\PengajuanRek::where('status', 'done')->count(),
                'antreanTerbaru' => [] 
            ]); 
        })->name('dashboard');

        // Manajemen User
        Route::resource('users', UserController::class);

        // Data Nasabah
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');

        // --- TAMBAHAN: TRACKING BANTUAN UNTUK PUPR ---
        // Agar route 'pupr.tracking.index' tersedia dan tidak error di sidebar
        Route::get('/tracking', [MonitoringController::class, 'trackingPage'])->name('tracking.index');
        Route::get('/tracking/search', [MonitoringController::class, 'doTracking'])->name('tracking.search');
        // Jika PUPR boleh update, uncomment baris bawah ini:
        // Route::put('/tracking/{id}', [MonitoringController::class, 'updateStatus'])->name('tracking.update');
    });


    // ================= GROUP 2: CABANG =================
    Route::middleware(['role:cabang'])->prefix('cabang')->name('cabang.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', function() {
            return view('cabang.dashboard', [
                'totalNasabah'   => \App\Models\Nasabah::count(),
                'pendingCount'   => \App\Models\PengajuanRek::whereIn('status', ['draft', 'process'])->count(),
                'readyCount'     => \App\Models\PengajuanRek::where('status', 'ready')->count(),
                'doneCount'      => \App\Models\PengajuanRek::where('status', 'done')->count(),
                'antreanTerbaru' => \App\Models\PengajuanRek::with('nasabah.user')->latest()->take(5)->get()
            ]);
        })->name('dashboard');

        // Manajemen Nasabah
        Route::get('nasabah/export', [NasabahController::class, 'export'])->name('nasabah.export');
        Route::post('nasabah/import', [NasabahController::class, 'import'])->name('nasabah.import');
        Route::resource('nasabah', NasabahController::class);

        // Tracking Bantuan
        Route::get('/tracking', [MonitoringController::class, 'trackingPage'])->name('tracking.index');
        Route::put('/tracking/{id}', [MonitoringController::class, 'updateStatus'])->name('tracking.update');
        Route::get('/tracking/{id}/print', [MonitoringController::class, 'cetakPdfDetail'])->name('tracking.print');
        Route::get('/tracking/search', [MonitoringController::class, 'doTracking'])->name('tracking.search');
        
        // Tambahan untuk cetak massal (Opsional, agar tidak error jika dipanggil di view)
        Route::get('/tracking/cetak-semua', [MonitoringController::class, 'cetakPdf'])->name('tracking.cetak');
    });


    // ================= PROFILE =================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});