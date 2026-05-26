<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'admin1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin1'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'admin2',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'user1',
            'email' => 'user@user.com',
            'password' => bcrypt('user1'),
            'is_admin' => false,
        ]);
    }
}
