<?php
// database/seeders/RatingSeeder.php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\LandlordProfile;
use App\Models\TenantProfile;
use App\Models\Property;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $landlords = LandlordProfile::all();
        $tenants = TenantProfile::all();

        // Track created combinations to avoid duplicates
        $createdCombinations = [];

        foreach ($landlords as $landlord) {
            $properties = $landlord->properties;

            if ($properties->isEmpty()) {
                continue; // Skip if landlord has no properties
            }

            // Create 2-3 unique ratings per landlord
            $ratingsCount = min(rand(2, 3), $tenants->count());

            $usedTenants = collect();

            for ($i = 0; $i < $ratingsCount; $i++) {
                // Get a tenant that hasn't rated this landlord yet
                $availableTenants = $tenants->diff($usedTenants);
                
                if ($availableTenants->isEmpty()) {
                    break; // No more unique tenants available
                }

                $tenant = $availableTenants->random();
                $property = $properties->random();

                // Create unique combination key
                $combinationKey = "{$landlord->id}-{$tenant->id}-{$property->id}";

                // Skip if this combination already exists
                if (in_array($combinationKey, $createdCombinations)) {
                    continue;
                }

                Rating::create([
                    'landlord_id' => $landlord->id,
                    'tenant_id' => $tenant->id,
                    'property_id' => $property->id,
                    'communication_rating' => rand(3, 5),
                    'accuracy_rating' => rand(3, 5),
                    'cleanliness_rating' => rand(3, 5),
                    'professionalism_rating' => rand(3, 5),
                    'fairness_rating' => rand(3, 5),
                    'review' => collect([
                        'Great landlord, very responsive!',
                        'Property was as described. Happy with the service.',
                        'Good communication, professional.',
                        'Nice property, landlord is helpful.',
                        'Everything went smoothly.',
                        'Excellent experience renting from this landlord.',
                        'Fair pricing and good maintenance.',
                    ])->random(),
                ]);

                $createdCombinations[] = $combinationKey;
                $usedTenants->push($tenant);
            }
        }

        $this->command->info('✅ Ratings created successfully!');
    }
}