<?php
// database/seeders/ListingSeeder.php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::where('status', 'published')->get();

        foreach ($properties as $property) {
            Listing::create([
                'property_id' => $property->id,
                'start_date' => Carbon::now(),
                'expiry_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'view_count' => rand(10, 200),
                'inquiry_count' => rand(2, 15),
            ]);
        }

        $this->command->info('✅ Listings created successfully!');
    }
}