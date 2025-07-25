<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        'store_id' => \App\Models\Store::factory(),
        'name' => fake()->name(),
        'description' => fake()->text(20),
        'price' => fake()->randomFloat(2, 0, 100),
        'stock' => fake()->randomFloat(10, 0, 100),

        ];
    }
}
