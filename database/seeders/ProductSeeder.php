<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
            ->count(5)
            ->meat()
            ->create();

        Product::factory()
            ->count(5)
            ->poultry()
            ->create();

        Product::factory()
            ->count(5)
            ->state(['category' => 'Колбаса'])
            ->create();
    }
}
