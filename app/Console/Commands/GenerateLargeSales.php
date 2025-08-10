<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerateLargeSales extends Command
{
    protected $signature = 'generate:sales {rows=15000000} {--batch=10000}';
    protected $description = 'Generate a large number of sales rows in batches';

    public function handle()
    {
        $rowsToGenerate = (int) $this->argument('rows');
        $batch = (int) $this->option('batch');

        $faker = Faker::create();

        // Disable query log to reduce memory usage during bulk insert
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        DB::disableQueryLog();

        $productIds = DB::table('products')->pluck('id')->toArray();
        $outletIds = DB::table('outlets')->pluck('id')->toArray();

        $this->info("Products: " . count($productIds) . " Outlets: " . count($outletIds));
        $startDate = Carbon::now()->subYear();

        $generated = 0;
        while ($generated < $rowsToGenerate) {
            $batchRows = [];
            $count = min($batch, $rowsToGenerate - $generated);

            for ($i = 0; $i < $count; $i++) {
                $productId = $productIds[array_rand($productIds)];
                $outletId = $outletIds[array_rand($outletIds)];
                $quantity = $faker->numberBetween(1, 30);
                $unitPrice = $faker->randomFloat(2, 0.5, 300.0);
                $date = $faker->dateTimeBetween($startDate, 'now')->format('Y-m-d H:i:s');

                $batchRows[] = [
                    'product_id' => $productId,
                    'outlet_id' => $outletId,
                    'date' => $date,
                    'quantity_sold' => $quantity,
                    'total_price' => round($quantity * $unitPrice, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('sales')->insert($batchRows);
            $generated += $count;

            $this->info("Inserted $generated / $rowsToGenerate");
        }

        $this->info("Done generating sales data.");
    }
}
