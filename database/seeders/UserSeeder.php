<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LandlordProfile;
use App\Models\TenantProfile;
use App\Models\AdminProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create first Admin User (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'admin@khomolanu.com'],
            [
                'name' => 'Admin User',
                'phone' => '+265888000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->adminProfile) {
            AdminProfile::create([
                'user_id' => $admin->id,
                'permission_level' => 'super_admin',
            ]);
        }

        // Second Admin User (create only if not exists)
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@khomolanu.com'],
            [
                'name' => 'Admin Two',
                'phone' => '+265888000002',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$admin2->adminProfile) {
            AdminProfile::create([
                'user_id' => $admin2->id,
                'permission_level' => 'moderator',
            ]);
        }

        // Create Verified Landlords
        $landlords = [
            [
                'name' => 'John Banda',
                'email' => 'john.banda@gmail.com',
                'phone' => '+265999111001',
                'business_name' => 'Banda Properties',
                'city' => 'Lilongwe',
                'address' => 'Area 47, Lilongwe',
            ],
            [
                'name' => 'Grace Phiri',
                'email' => 'grace.phiri@gmail.com',
                'phone' => '+265888222002',
                'business_name' => 'Phiri Estates',
                'city' => 'Blantyre',
                'address' => 'Limbe, Blantyre',
            ],
            [
                'name' => 'Peter Kachingwe',
                'email' => 'peter.k@gmail.com',
                'phone' => '+265991333003',
                'business_name' => 'Kachingwe Holdings',
                'city' => 'Mzuzu',
                'address' => 'Chibavi, Mzuzu',
            ],
            [
                'name' => 'Mary Chirwa',
                'email' => 'mary.chirwa@gmail.com',
                'phone' => '+265888444004',
                'business_name' => 'Chirwa Rentals',
                'city' => 'Zomba',
                'address' => 'Chinamwali, Zomba',
            ],
        ];

        foreach ($landlords as $landlordData) {
            $user = User::create([
                'name' => $landlordData['name'],
                'email' => $landlordData['email'],
                'phone' => $landlordData['phone'],
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);

            LandlordProfile::create([
                'user_id' => $user->id,
                'national_id' => 'MWI-' . rand(100000, 999999),
                'business_name' => $landlordData['business_name'],
                'address' => $landlordData['address'],
                'city' => $landlordData['city'],
                'verification_status' => 'approved',
                'verified_at' => now(),
                'avg_rating' => rand(40, 50) / 10, // 4.0-5.0
                'total_ratings' => rand(5, 20),
            ]);
        }

        // Create Tenants
        $tenants = [
            ['name' => 'David Mwale', 'email' => 'david.m@gmail.com', 'phone' => '+265999555001'],
            ['name' => 'Sarah Tembo', 'email' => 'sarah.t@gmail.com', 'phone' => '+265888666002'],
            ['name' => 'James Lungu', 'email' => 'james.l@gmail.com', 'phone' => '+265991777003'],
            ['name' => 'Ruth Ngwira', 'email' => 'ruth.n@gmail.com', 'phone' => '+265888888004'],
            ['name' => 'Patrick Mkandawire', 'email' => 'patrick.m@gmail.com', 'phone' => '+265999999005'],
        ];

        foreach ($tenants as $tenantData) {
            $user = User::create([
                'name' => $tenantData['name'],
                'email' => $tenantData['email'],
                'phone' => $tenantData['phone'],
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);

            TenantProfile::create([
                'user_id' => $user->id,
                'occupation' => collect(['Student', 'Professional', 'Business Owner', 'NGO Worker'])->random(),
                'preferences' => 'Looking for a clean, affordable property',
            ]);
        }

        $this->command->info('✅ Users, Landlords, Tenants, and Admin created successfully!');
    }
}