<?php

namespace Database\Seeders;

use Database\Seeders\SpecialtySeeder;
use Database\Seeders\BlogSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optionally create some demo data
        $this->call([
            SpecialtySeeder::class,
            BlogSeeder::class,
        ]);
    }
}
