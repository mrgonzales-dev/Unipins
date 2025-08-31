<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get(
    '/', function () {
        return view('welcome');
    }
);

// Resourceful routes for items
// Mainly for crud functionalities
Route::resource('items', ItemController::class)->middleware(['auth', 'verified']);

// Make /dashboard point to items.index
// Mainly for dashboard view
Route::get('/dashboard', [ItemController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');




Route::middleware('auth')->group(
    function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    }
);

require __DIR__.'/auth.php';
