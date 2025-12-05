<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizerController; // Jangan lupa import ini
use App\Http\Controllers\AdminController;     // Jangan lupa import ini
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('events', EventController::class)->except(['index', 'show']);

    // --- Route Khusus Customer (Calon EO) ---
    // Kita panggil Class Middleware secara langsung
    Route::middleware(EnsureCustomer::class)->group(function () {
        Route::get('/organizer/apply', [OrganizerController::class, 'create'])->name('organizer.create');
        Route::post('/organizer/apply', [OrganizerController::class, 'store'])->name('organizer.store');
    });


    // --- Route Khusus Admin ---
    // Kita panggil Class Middleware secara langsung
    Route::middleware(EnsureAdmin::class)->prefix('admin')->group(function () {

        Route::get('/verifications', [AdminController::class, 'pendingOrganizers'])
            ->name('admin.verifications');

        Route::patch('/approve/{id}', [AdminController::class, 'approveOrganizer'])
            ->name('admin.approve');
    });
});

require __DIR__ . '/auth.php';
