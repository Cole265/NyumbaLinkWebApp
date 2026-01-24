<?php
// database/seeders/PropertySeeder.php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyAmenity;
use App\Models\LandlordProfile;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $landlords = LandlordProfile::all();

        $properties = [
            // LILONGWE PROPERTIES
            [
                'landlord_id' => $landlords->where('city', 'Lilongwe')->first()->id,
                'property_type' => 'residential',
                'title' => '3 Bedroom House in Area 47',
                'description' => 'Beautiful 3 bedroom house with spacious compound, modern kitchen, and secure wall fence. Close to schools and shopping centers.',
                'city' => 'Lilongwe',
                'district' => 'Lilongwe',
                'area' => 'Area 47',
                'price' => 350000,
                'currency' => 'MWK',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'size_sqm' => 120,
                'is_furnished' => false,
                'latitude' => -13.9626,
                'longitude' => 33.7741,
                'status' => 'published',
                'amenities' => ['Water tank', 'Parking', 'Security fence', 'ESCOM power'],
            ],
            [
                'landlord_id' => $landlords->where('city', 'Lilongwe')->first()->id,
                'property_type' => 'commercial',
                'title' => 'Shop in City Centre',
                'description' => 'Prime location shop space in Lilongwe City Centre. High foot traffic, perfect for retail business.',
                'city' => 'Lilongwe',
                'district' => 'Lilongwe',
                'area' => 'City Centre',
                'price' => 800000,
                'currency' => 'MWK',
                'bedrooms' => null,
                'bathrooms' => 1,
                'size_sqm' => 60,
                'is_furnished' => false,
                'latitude' => -13.9833,
                'longitude' => 33.7833,
                'status' => 'published',
                'amenities' => ['Main road access', 'Security', 'Parking space'],
            ],
            [
                'landlord_id' => $landlords->where('city', 'Lilongwe')->first()->id,
                'property_type' => 'land',
                'title' => 'Residential Plot in Area 49',
                'description' => '0.5 acre plot with title deed. Flat terrain, ready for construction.',
                'city' => 'Lilongwe',
                'district' => 'Lilongwe',
                'area' => 'Area 49',
                'price' => 15000000,
                'currency' => 'MWK',
                'bedrooms' => null,
                'bathrooms' => null,
                'size_sqm' => 2023,
                'is_furnished' => false,
                'latitude' => -13.9500,
                'longitude' => 33.7600,
                'status' => 'published',
                'amenities' => ['Title deed available', 'ESCOM nearby', 'Water access'],
            ],

            // BLANTYRE PROPERTIES
            [
                'landlord_id' => $landlords->where('city', 'Blantyre')->first()->id,
                'property_type' => 'residential',
                'title' => '2 Bedroom Apartment in Limbe',
                'description' => 'Modern 2 bedroom apartment with fitted kitchen, balcony, and secure parking.',
                'city' => 'Blantyre',
                'district' => 'Blantyre',
                'area' => 'Limbe',
                'price' => 280000,
                'currency' => 'MWK',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'size_sqm' => 80,
                'is_furnished' => true,
                'latitude' => -15.8011,
                'longitude' => 35.0375,
                'status' => 'published',
                'amenities' => ['Furnished', 'Parking', 'Water tank', 'Generator backup'],
            ],
            [
                'landlord_id' => $landlords->where('city', 'Blantyre')->first()->id,
                'property_type' => 'residential',
                'title' => '4 Bedroom House in Namiwawa',
                'description' => 'Spacious family home with large garden, servant quarters, and borehole.',
                'city' => 'Blantyre',
                'district' => 'Blantyre',
                'area' => 'Namiwawa',
                'price' => 450000,
                'currency' => 'MWK',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'size_sqm' => 180,
                'is_furnished' => false,
                'latitude' => -15.7866,
                'longitude' => 35.0300,
                'status' => 'published',
                'amenities' => ['Borehole', 'Servant quarters', 'Large compound', 'Security wall'],
            ],

            // MZUZU PROPERTIES
            [
                'landlord_id' => $landlords->where('city', 'Mzuzu')->first()->id,
                'property_type' => 'residential',
                'title' => 'Student Bedsitter in Chibavi',
                'description' => 'Affordable bedsitter perfect for students. Close to Mzuzu University.',
                'city' => 'Mzuzu',
                'district' => 'Mzimba',
                'area' => 'Chibavi',
                'price' => 80000,
                'currency' => 'MWK',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'size_sqm' => 25,
                'is_furnished' => false,
                'latitude' => -11.4593,
                'longitude' => 34.0154,
                'status' => 'published',
                'amenities' => ['Water', 'Electricity', 'Secure area'],
            ],
            [
                'landlord_id' => $landlords->where('city', 'Mzuzu')->first()->id,
                'property_type' => 'commercial',
                'title' => 'Office Space in Mzuzu CBD',
                'description' => 'Professional office space with reception area and parking.',
                'city' => 'Mzuzu',
                'district' => 'Mzimba',
                'area' => 'CBD',
                'price' => 500000,
                'currency' => 'MWK',
                'bedrooms' => null,
                'bathrooms' => 2,
                'size_sqm' => 100,
                'is_furnished' => false,
                'latitude' => -11.4500,
                'longitude' => 34.0200,
                'status' => 'published',
                'amenities' => ['Parking', 'Security', 'ESCOM', 'Water'],
            ],

            // ZOMBA PROPERTIES
            [
                'landlord_id' => $landlords->where('city', 'Zomba')->first()->id,
                'property_type' => 'residential',
                'title' => '3 Bedroom House in Chinamwali',
                'description' => 'Family house near Zomba town center. Good condition with large yard.',
                'city' => 'Zomba',
                'district' => 'Zomba',
                'area' => 'Chinamwali',
                'price' => 250000,
                'currency' => 'MWK',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'size_sqm' => 110,
                'is_furnished' => false,
                'latitude' => -15.3850,
                'longitude' => 35.3188,
                'status' => 'published',
                'amenities' => ['Water tank', 'Parking', 'Fence', 'ESCOM'],
            ],
        ];

        foreach ($properties as $propertyData) {
            $amenities = $propertyData['amenities'];
            unset($propertyData['amenities']);

            $property = Property::create($propertyData);

            // Add amenities
            foreach ($amenities as $amenity) {
                PropertyAmenity::create([
                    'property_id' => $property->id,
                    'amenity' => $amenity,
                ]);
            }

            // Add placeholder images (you can replace with real images later)
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => 'properties/placeholder-' . $property->id . '-1.jpg',
                'is_primary' => true,
                'order' => 1,
            ]);

            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => 'properties/placeholder-' . $property->id . '-2.jpg',
                'is_primary' => false,
                'order' => 2,
            ]);
        }

        $this->command->info('✅ Properties created successfully!');
    }
}