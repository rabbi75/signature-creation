<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerateLargeSales extends Command
{
    protected $signature = 'generate:sales {rows=15000000} {--batch=2000} {--max-retries=3}';

    protected $description = 'Generate a large number of sales rows in batches with error handling and memory management';

    public function handle()
    {
        ini_set('memory_limit', '2048M'); // Increase memory limit

        $rowsToGenerate = (int) $this->argument('rows');
        $batchSize = (int) $this->option('batch');
        $maxRetries = (int) $this->option('max-retries');

        $faker = Faker::create();

        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        DB::disableQueryLog();

        $this->info("Loading product and outlet IDs...");
        $productIds = DB::table('products')->pluck('id')->toArray();
        $outletIds = DB::table('outlets')->pluck('id')->toArray();

        if (empty($productIds) || empty($outletIds)) {
            $this->error('No products or outlets found. Please seed these first.');
            return 1;
        }

        $this->info("Loaded " . count($productIds) . " products and " . count($outletIds) . " outlets.");

        $startDate = Carbon::now()->subYear();

        $inserted = 0;

        $bar = $this->output->createProgressBar($rowsToGenerate);
        $bar->start();

        while ($inserted < $rowsToGenerate) {
            $rows = [];
            $currentBatchCount = min($batchSize, $rowsToGenerate - $inserted);

            for ($i = 0; $i < $currentBatchCount; $i++) {
                $productId = $productIds[array_rand($productIds)];
                $outletId = $outletIds[array_rand($outletIds)];
                $quantity = $faker->numberBetween(1, 30);
                $unitPrice = $faker->randomFloat(2, 0.5, 300.0);
                $date = $faker->dateTimeBetween($startDate, 'now')->format('Y-m-d H:i:s');

                $rows[] = [
                    'product_id' => $productId,
                    'outlet_id' => $outletId,
                    'date' => $date,
                    'quantity_sold' => $quantity,
                    'total_price' => round($quantity * $unitPrice, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $attempts = 0;
            $insertedSuccessfully = false;

            while (!$insertedSuccessfully && $attempts < $maxRetries) {
                try {
                    DB::table('sales')->insert($rows);
                    $insertedSuccessfully = true;
                } catch (\Exception $e) {
                    $attempts++;
                    Log::error("Batch insert failed (attempt $attempts): " . $e->getMessage());
                    $this->error("Batch insert failed (attempt $attempts): " . $e->getMessage());
                    sleep(1); // wait a bit before retrying
                }
            }

            if (!$insertedSuccessfully) {
                $this->error("Max retries reached. Aborting.");
                return 1;
            }

            $inserted += $currentBatchCount;
            $bar->advance($currentBatchCount);

            // Free memory
            unset($rows);
            gc_collect_cycles();
        }

        $bar->finish();
        $this->info("\nFinished generating $rowsToGenerate sales records.");

        return 0;
    }
}
