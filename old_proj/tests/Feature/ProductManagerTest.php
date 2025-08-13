<?php

use Livewire\Livewire;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

//Viewing Product Test
it ('Can Create Seller User', function() {


    $seller = \App\Models\User::factory()->create();
    $response = $this->actingAs($seller)->get(route('seller.store-manager'));
    $response = $response->assertStatus(200)->assertSee($seller->name);

});

it('Seller Can Access Store Manager Dashboard' , function() {

    $seller = \App\Models\User::factory()->create();
    $store = \App\Models\Store::factory()->for($seller)->create();
    $response = $this->actingAs($seller)->get(route('seller.store-manager', $store));
    $response = $response->assertStatus(200)->assertSee($store->name);

});

it('Can Create Product for Store' , function() {

    $seller = \App\Models\User::factory()->create();
    $store = \App\Models\Store::factory()->create([
        'user_id' => $seller->id
    ]);
    $product = \App\Models\Products::factory()->create([
        'store_id' => $store->id
    ]);

    Livewire::actingAs($seller)
        ->test(\App\Livewire\Seller\ProductManager::class, ['storeId' => $store->id])
        ->assertSee($product->name);
});


