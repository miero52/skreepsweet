<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PimpinanController;

// Route debug - tambahkan di atas routes lain
Route::get('/debug-auth', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'authenticated' => true,
            'user' => $user->toArray(),
            'is_petugas' => $user->isPetugas(),
            'is_masyarakat' => $user->isMasyarakat(),
            'is_pimpinan' => $user->isPimpinan()
        ]);
    }
    return response()->json(['authenticated' => false]);
})->middleware('auth');

Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum login, tampilkan welcome page
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:masyarakat')->group(function () {
        // Profile Routes - accessible for both roles
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // Routes untuk Masyarakat
    Route::middleware('role:masyarakat')->group(function () {
        Route::get('/masyarakat/dashboard', [MasyarakatController::class, 'dashboard'])->name('masyarakat.dashboard');
        Route::get('/masyarakat/permohonan/create', [MasyarakatController::class, 'createPermohonan'])->name('masyarakat.create-permohonan');
        Route::post('/masyarakat/permohonan', [MasyarakatController::class, 'storePermohonan'])->name('masyarakat.store-permohonan');
        Route::get('/masyarakat/download/{id}', [MasyarakatController::class, 'downloadHasil'])->name('masyarakat.download-hasil');
    });

    // Routes untuk Petugas
    Route::middleware('role:petugas')->group(function () {
        Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
        Route::get('/petugas/permohonan/{id}', [PetugasController::class, 'showPermohonan'])->name('petugas.show-permohonan');
        Route::patch('/petugas/permohonan/{id}/status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');
        // Export routes
        Route::get('/petugas/export-pdf', [PetugasController::class, 'exportPDF'])->name('petugas.export-pdf');
        Route::get('/petugas/laporan', [PetugasController::class, 'laporanPage'])->name('petugas.laporan');

        // Manajemen User oleh Petugas (Admin Sistem)
        Route::get('/petugas/users', [PetugasController::class, 'indexUsers'])->name('petugas.users.index');
        Route::get('/petugas/users/{id}/edit', [PetugasController::class, 'editUser'])->name('petugas.users.edit');
        Route::patch('/petugas/users/{id}', [PetugasController::class, 'updateUser'])->name('petugas.users.update');
        Route::delete('/petugas/users/{id}', [PetugasController::class, 'destroyUser'])->name('petugas.users.destroy');
    });

    Route::middleware('role:pimpinan')->group(function () {
        Route::get('/pimpinan/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/pimpinan/monitoring', [PimpinanController::class, 'monitoring'])->name('pimpinan.monitoring');
        Route::patch('/pimpinan/permohonan/{id}/approval', [PimpinanController::class, 'updateApproval'])->name('pimpinan.update-approval');
        Route::get('/pimpinan/laporan', [PimpinanController::class, 'laporanPage'])->name('pimpinan.laporan');
        Route::get('/pimpinan/export-laporan', [PimpinanController::class, 'exportLaporan'])->name('pimpinan.export-laporan');
        Route::get('/pimpinan/permohonan/{id}', [PimpinanController::class, 'show'])->name('pimpinan.permohonan.show');
    });

    // Tambahkan di dalam middleware auth group
    Route::post('/notifications/mark-all-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.mark-all-read');
});

require __DIR__ . '/auth.php';
