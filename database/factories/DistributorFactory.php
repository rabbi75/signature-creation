<?php

namespace Database\Factories;

use App\Models\Distributor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistributorFactory extends Factory
{
    protected $model = Distributor::class;

    public function definition()
    {
        $regions = ['North', 'South', 'East', 'West', 'Central'];

        return [
            'name' => $this->faker->company,
            'region' => $this->faker->randomElement($regions),
            'contact_info' => $this->faker->phoneNumber,
        ];
    }
}
