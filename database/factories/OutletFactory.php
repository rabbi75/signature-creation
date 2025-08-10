<?php

namespace Database\Factories;

use App\Models\Outlet;
use App\Models\Distributor;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutletFactory extends Factory
{
    protected $model = Outlet::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Outlet',
            'address' => $this->faker->address,
            // distributor_id will be set in InitialDataSeeder after distributors are created
            'distributor_id' => null,
        ];
    }
}
