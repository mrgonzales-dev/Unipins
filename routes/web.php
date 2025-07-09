<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// seller livewire.dashboard
use App\Livewire\Seller\Dashboard as SellerDashboard;
// buy livewire.dashboard
use App\Livewire\Buyer\Dashboard as BuyerDashboard;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // seller dashboard
    Route::get('seller/dashboard', SellerDashboard::class)->name('seller.dashboard');

    // buyer dashboard
    Route::get('buyer/dashboard', BuyerDashboard::class)->name('buyer.dashboard');


    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
