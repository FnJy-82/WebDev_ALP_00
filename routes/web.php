<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\GatekeeperController;
use App\Http\Controllers\OrganizerApiKeyController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureCustomer;
use App\Http\Middleware\EnsureKycCompleted;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Halaman Depan)
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
    $events = Event::latest()->take(6)->get(); 
    return view('home', ['events' => $events]);
})->name('home');

// List Semua Event (Public)
Route::get('/events', [EventController::class, 'index'])->name('events.index');


/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED ROUTES (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Event Management (CREATE, STORE, EDIT, UPDATE, DESTROY) ---
    // PENTING: Resource ini harus didefinisikan SEBELUM route public 'show' di bawah
    Route::resource('events', EventController::class)->except(['index', 'show']);

    // --- Transaksi ---
    Route::middleware([EnsureKycCompleted::class])->group(function () {
        Route::get('/checkout/{event}', [TransactionController::class, 'create'])->name('checkout.create');
        Route::post('/checkout', [TransactionController::class, 'store'])->name('transaction.store');
    });

    // --- Tiket & Wallet ---
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/my-tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

    // --- Gatekeeper ---
    Route::get('/gatekeeper/scan', [GatekeeperController::class, 'index'])->name('gatekeeper.scan');
    Route::post('/gatekeeper/checkin', [GatekeeperController::class, 'store'])->name('gatekeeper.checkin');

    // --- Organizer Tools ---
    Route::middleware(EnsureCustomer::class)->group(function () {
        Route::get('/organizer/apply', [OrganizerController::class, 'create'])->name('organizer.create');
        Route::post('/organizer/apply', [OrganizerController::class, 'store'])->name('organizer.store');
    });

    Route::get('/organizer/api-key', [OrganizerApiKeyController::class, 'index'])->name('organizer.apikey');
    Route::post('/organizer/api-key', [OrganizerApiKeyController::class, 'generate'])->name('organizer.apikey.generate');

    // --- Admin ---
    Route::middleware(EnsureAdmin::class)->prefix('admin')->group(function () {
        Route::get('/verifications', [AdminController::class, 'pendingOrganizers'])->name('admin.verifications');
        Route::patch('/approve/{id}', [AdminController::class, 'approveOrganizer'])->name('admin.approve');
    });
});


/*
|--------------------------------------------------------------------------
| 3. PUBLIC EVENT DETAIL (TARUH PALING BAWAH!)
|--------------------------------------------------------------------------
| Kenapa di bawah? Supaya URL "/events/create" tidak dianggap sebagai 
| "/events/{id}" oleh Laravel.
*/
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');


require __DIR__ . '/auth.php';