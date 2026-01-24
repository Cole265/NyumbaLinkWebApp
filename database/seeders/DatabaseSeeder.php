<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PropertySeeder::class,
            ListingSeeder::class,
            RatingSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
    }
}