<?php

use App\Models\User;

// Test: Public welcome page
test('displays the welcome page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

// Test: Guest redirect to login
test('redirects unauthenticated users from seller dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

