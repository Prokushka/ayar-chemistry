<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prodile>
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
            'title' => fake()->realText(35),
            'description' => '',
            'sku' => null,
            'image_url' => fake()->imageUrl(),
            'purchase_price' => fake()->numberBetween(100, 150),
            'quantity' => fake()->numberBetween(11, 22),
            'is_active' => 1,
            'slug' => fake()->slug(),
            'size' => '1 кг'
        ];
    }
}
