<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $units = ['kg', 'litre', 'piece'];
        $categories = ['Beverages', 'Snacks', 'Dairy', 'Bakery', 'Frozen Foods', 'Produce'];

        return [
            'name' => $this->faker->unique()->words(3, true),
            'sku' => strtoupper($this->faker->unique()->bothify('???-#####')),
            'category' => $this->faker->randomElement($categories),
            'unit' => $this->faker->randomElement($units),
            'price' => $this->faker->randomFloat(2, 0.5, 500),
        ];
    }
}
