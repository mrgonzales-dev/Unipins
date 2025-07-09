<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// roles models
use App\Livewire\Buyer\Dashboard as buyerDashboard;
use App\Livewire\Seller\Dashboard as sellerDashboard;

// Product Catalogue
use App\Livewire\Seller\ProductCatalogue as ProductCatalogue;
use App\Livewire\Buyer\ProductCatalogue as buyerProductCatalogue;

// Store Manager
use App\Livewire\Seller\StoreManager as StoreManager;

// Product Manager
use App\Livewire\Seller\ProductManager as ProductManager;

use function Livewire\Volt\protect;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('buyer/dashboard', buyerDashboard::class)->middleware(['role:buyer'])->name('buyer.dashboard');
    Route::get('seller/dashboard', sellerDashboard::class)->middleware(['role:seller'])->name('seller.dashboard');

    // Product Catalogue
    Route::get('seller/product-catalogue', ProductCatalogue::class)->middleware(['role:seller'])->name('seller.product-catalogue');
    Route::get('buyer/product-catalogue', buyerProductCatalogue::class)->middleware(['role:buyer'])->name('buyer.product-catalogue');

    //Store Manager
    Route::get('seller/store-manager', StoreManager::class)->middleware(['role:seller'])->name('seller.store-manager');

    //Product Manager
    Route::get('seller/product-manager/{storeId}', ProductManager::class)
        ->middleware(['auth', 'role:seller'])
        ->name('seller.product-manager');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
