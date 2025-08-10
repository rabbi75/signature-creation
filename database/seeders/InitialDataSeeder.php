<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Distributor;
use App\Models\Outlet;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create 5000 products
        Product::factory()->count(5000)->create();

        // Create 5000 distributors
        Distributor::factory()->count(5000)->create();

        // Get all distributor IDs for assigning to outlets
        $distributorIds = Distributor::pluck('id')->toArray();

        // Create 50,000 outlets and assign random distributors
        $batchSize = 1000;
        $totalOutlets = 50000;

        for ($i = 0; $i < $totalOutlets; $i += $batchSize) {
            $outlets = [];
            $count = min($batchSize, $totalOutlets - $i);

            for ($j = 0; $j < $count; $j++) {
                $outlets[] = [
                    'name' => fake()->company . ' Outlet',
                    'address' => fake()->address,
                    'distributor_id' => $distributorIds[array_rand($distributorIds)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Outlet::insert($outlets);
        }
    }
}
