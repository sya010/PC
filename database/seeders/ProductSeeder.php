<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products to prevent duplicates during testing
        Product::truncate();

        // PC Components (100 each)
        $this->command->info('Seeding CPU...');
        Product::factory()->count(100)->cpu()->create();

        $this->command->info('Seeding Motherboards...');
        Product::factory()->count(100)->motherboard()->create();

        $this->command->info('Seeding GPUs...');
        Product::factory()->count(100)->gpu()->create();

        $this->command->info('Seeding RAM...');
        Product::factory()->count(100)->ram()->create();

        $this->command->info('Seeding Storage...');
        Product::factory()->count(100)->storage()->create();

        $this->command->info('Seeding PSUs...');
        Product::factory()->count(100)->psu()->create();

        $this->command->info('Seeding Cases...');
        Product::factory()->count(100)->case()->create();

        $this->command->info('Seeding Cooling...');
        Product::factory()->count(100)->cooling()->create();

        // Peripherals (50 each)
        $this->command->info('Seeding Mice...');
        Product::factory()->count(50)->mouse()->create();

        $this->command->info('Seeding Mousepads...');
        Product::factory()->count(50)->mousepad()->create();

        $this->command->info('Seeding Headsets...');
        Product::factory()->count(50)->headset()->create();

        $this->command->info('Seeding Microphones...');
        Product::factory()->count(50)->microphone()->create();

        $this->command->info('Seeding Keyboards...');
        Product::factory()->count(50)->keyboard()->create();

        $this->command->info('Seeding Monitors...');
        Product::factory()->count(50)->monitor()->create();

        $this->command->info('Seeding Webcams...');
        Product::factory()->count(50)->webcam()->create();

        $this->command->info('Seeding Speakers...');
        Product::factory()->count(50)->speakers()->create();

        $this->command->info('Database seeded with ' . Product::count() . ' products across all categories.');
    }
}
