<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        // We'll not randomly pick outlet/product here because seeder will supply ids for performance.
        // This factory can be used for small scale tests.
        $quantity = $this->faker->numberBetween(1, 20);
        $price = $this->faker->randomFloat(2, 0.5, 500.0);

        // realistic timestamp within the last year
        $start = Carbon::now()->subYear();
        $date = $this->faker->dateTimeBetween($start, 'now');

        return [
            'outlet_id' => null, // set in seeder for large data
            'product_id' => null, // set in seeder for large data
            'date' => $date,
            'quantity_sold' => $quantity,
            'total_price' => round($quantity * $price, 2),
        ];
    }
}
