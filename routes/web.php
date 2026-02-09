<?php

use Illuminate\Support\Facades\Route;
use App\Models\Boat;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('auth')->group(function () {
    // Страница создания бронирования
    Route::get('/boats/{boat}/book', [BookingController::class, 'create'])
        ->name('booking.create');
    
    // Обработка бронирования
    Route::post('/boats/{boat}/book', [BookingController::class, 'store'])
        ->name('booking.store');
    
    // Список моих бронирований
    Route::get('/my-bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');
});
Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])
    ->name('bookings.pay');
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::resource('boats', \App\Http\Controllers\Admin\BoatController::class);
});

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');
require __DIR__.'/settings.php';
