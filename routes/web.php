<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NasabahRegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Nasabah;
use App\Models\PengajuanRek;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Awal langsung lempar ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================================
// ROUTE PUBLIK (PENDAFTARAN MANDIRI)
// =========================================================================
Route::get('/pengajuan-nasabah', [NasabahRegistrationController::class, 'create'])->name('nasabah.register.create');
Route::post('/pengajuan-nasabah', [NasabahRegistrationController::class, 'store'])->name('nasabah.register.store');

// =========================================================================
// ROUTE AUTH (LOGIN, REGISTER, VERIFY EMAIL)
// =========================================================================
require __DIR__.'/auth.php';


// =========================================================================
// AREA YANG MEMBUTUHKAN LOGIN
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- LOGIKA REDIRECT DASHBOARD ---
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'Admin' || $user->username === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'Funding') {
            return redirect()->route('funding.dashboard');
        }

        return redirect('/');
    })->name('dashboard');


    // ================= GROUP KHUSUS ADMIN =================
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // 1. Dashboard Admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'totalNasabah'   => Nasabah::count(),
                'pendingCount'   => PengajuanRek::whereIn('status', ['draft', 'process'])->count(),
                'readyCount'     => PengajuanRek::where('status', 'ready')->count(),
                'doneCount'      => PengajuanRek::where('status', 'done')->count(),
                'antreanTerbaru' => PengajuanRek::with('nasabah.user')->latest()->take(5)->get()
            ]); 
        })->name('dashboard');

        // 2. Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{id}/status', [UserController::class, 'toggleStatus'])->name('users.status');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // 3. Manajemen Nasabah (LENGKAP)
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
        Route::get('/nasabah/export-excel', [NasabahController::class, 'export'])->name('nasabah.export'); 
        Route::post('/nasabah/import', [NasabahController::class, 'import'])->name('nasabah.import');
        Route::get('/nasabah/create', [NasabahController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah', [NasabahController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{id}/edit', [NasabahController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{id}', [NasabahController::class, 'update'])->name('nasabah.update');
        Route::delete('/nasabah/{id}', [NasabahController::class, 'destroy'])->name('nasabah.destroy');
        Route::get('/nasabah/{id}', [NasabahController::class, 'show'])->name('nasabah.show');

        // 4. Tracking
        Route::get('/tracking', [MonitoringController::class, 'trackingPage'])->name('tracking.index');
        Route::get('/tracking/detail', [MonitoringController::class, 'doTracking'])->name('tracking.show');
        Route::post('/update-status/{id}', [MonitoringController::class, 'updateStatus'])->name('updateStatus');
        
        Route::get('/tracking/cetak-tanda-terima', [MonitoringController::class, 'cetakPdf'])->name('tracking.print');
        Route::get('/tracking/cetak-tanda-terima/{id}', [MonitoringController::class, 'cetakPdfDetail'])->name('tracking.print.detail');
    });


    // ================= GROUP KHUSUS FUNDING =================
    Route::middleware(['role:Funding'])->prefix('funding')->name('funding.')->group(function () {
        
        // 1. Dashboard Funding
        Route::get('/dashboard', [MonitoringController::class, 'index'])->name('dashboard');

        // 2. Manajemen Nasabah (LENGKAP)
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
        Route::get('/nasabah/export-excel', [NasabahController::class, 'export'])->name('nasabah.export');
        Route::post('/nasabah/import', [NasabahController::class, 'import'])->name('nasabah.import');
        Route::get('/nasabah/create', [NasabahController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah', [NasabahController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{id}/edit', [NasabahController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{id}', [NasabahController::class, 'update'])->name('nasabah.update');
        Route::delete('/nasabah/{id}', [NasabahController::class, 'destroy'])->name('nasabah.destroy');
        Route::get('/nasabah/{id}', [NasabahController::class, 'show'])->name('nasabah.show');

        // 3. Tracking Berkas
        Route::get('/tracking', [MonitoringController::class, 'trackingPage'])->name('tracking.index');
        Route::get('/tracking/detail', [MonitoringController::class, 'doTracking'])->name('tracking.show');
        Route::post('/update-status/{id}', [MonitoringController::class, 'updateStatus'])->name('updateStatus'); // nama: funding.updateStatus
        
        // Cetak PDF
        Route::get('/tracking/cetak-tanda-terima', [MonitoringController::class, 'cetakPdf'])->name('tracking.print');
        Route::get('/tracking/cetak-tanda-terima/{id}', [MonitoringController::class, 'cetakPdfDetail'])->name('tracking.print.detail');
    });


    // ================= PROFILE (ADMIN & FUNDING) =================
    Route::middleware(['role:Admin,Funding'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});