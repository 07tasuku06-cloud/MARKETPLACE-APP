<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'name' => $this->faker->word(),

            'brand' => $this->faker->company(),

            'description' => $this->faker->sentence(),

            'price' => $this->faker->numberBetween(1000, 50000),

            'condition' => '良好',

            'image' => '/images/test.jpg',

            'is_sold' => false,
        ];
    }
}
