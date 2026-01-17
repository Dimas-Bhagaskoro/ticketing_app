<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as EventController;
use App\Http\Controllers\Admin\HistoriesController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Category Management
        Route::resource('categories', CategoryController::class);
        // Event Management
        Route::resource('events', EventController::class);
        // Histories Management
        Route::get('/histories', [HistoriesController::class, 'index'])
            ->name('histories.index');
        Route::get('/histories/{history}', [HistoriesController::class, 'show'])
            ->name('histories.show');
    });

});

require __DIR__.'/auth.php';
