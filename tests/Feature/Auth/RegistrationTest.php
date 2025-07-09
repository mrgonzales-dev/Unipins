<?php

use App\Livewire\Auth\Register;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new sellers can register', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('phone', '1234567890')
        ->set('address', '123 Main St')
        ->set('role', 'seller')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('seller.dashboard', absolute: false));

    $this->assertAuthenticated();
});


test('new buyers can register', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('phone', '1234567890')
        ->set('address', '123 Main St')
        ->set('role', 'buyer')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('buyer.dashboard', absolute: false));

    $this->assertAuthenticated();
});
