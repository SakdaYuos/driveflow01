<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'admin@driveflow.kh'],
            [
                'name'     => 'DriveFlow Admin',
                'email'    => 'admin@driveflow.kh',
                'password' => Hash::make('Admin@2025'),
                'is_admin' => true,
                'phone'    => '+855 23 000 001',
            ]
        );

        // Demo customer
        User::updateOrCreate(
            ['email' => 'demo@driveflow.kh'],
            [
                'name'     => 'Demo Customer',
                'email'    => 'demo@driveflow.kh',
                'password' => Hash::make('Demo@2025'),
                'is_admin' => false,
                'phone'    => '+855 12 345 678',
            ]
        );

        // Sample cars
        $cars = [
            [
                'brand' => 'Toyota', 'model' => 'Camry', 'year' => 2022,
                'license_plate' => 'PP-1234A', 'price_per_day' => 60,
                'status' => 'Available', 'type' => 'Sedan',
                'city' => 'Phnom Penh', 'fuel_type' => 'Petrol',
                'car_seat' => '5', 'description' => 'Comfortable family sedan with modern features.',
            ],
            [
                'brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2023,
                'license_plate' => 'PP-5678B', 'price_per_day' => 90,
                'status' => 'Available', 'type' => 'SUV',
                'city' => 'Phnom Penh', 'fuel_type' => 'Diesel',
                'car_seat' => '7', 'description' => 'Powerful SUV perfect for city and countryside.',
            ],
            [
                'brand' => 'Honda', 'model' => 'Civic', 'year' => 2021,
                'license_plate' => 'SR-1111C', 'price_per_day' => 45,
                'status' => 'Available', 'type' => 'Sedan',
                'city' => 'Siem Reap', 'fuel_type' => 'Petrol',
                'car_seat' => '5', 'description' => 'Fuel-efficient compact car, great for exploring Siem Reap.',
            ],
            [
                'brand' => 'Hyundai', 'model' => 'Starex', 'year' => 2020,
                'license_plate' => 'PP-9999D', 'price_per_day' => 110,
                'status' => 'Available', 'type' => 'Van',
                'city' => 'Phnom Penh', 'fuel_type' => 'Diesel',
                'car_seat' => '12', 'description' => 'Spacious van ideal for group travel.',
            ],
            [
                'brand' => 'Lexus', 'model' => 'LX570', 'year' => 2023,
                'license_plate' => 'SHV-222E', 'price_per_day' => 180,
                'status' => 'Available', 'type' => 'SUV',
                'city' => 'Sihanoukville', 'fuel_type' => 'Petrol',
                'car_seat' => '7', 'description' => 'Luxury SUV for premium coastal travel.',
            ],
            [
                'brand' => 'Mitsubishi', 'model' => 'Outlander', 'year' => 2022,
                'license_plate' => 'PP-3456F', 'price_per_day' => 75,
                'status' => 'Available', 'type' => 'SUV',
                'city' => 'Poi Pet', 'fuel_type' => 'Petrol',
                'car_seat' => '5', 'description' => 'Reliable crossover for border city trips.',
            ],
        ];

        foreach ($cars as $carData) {
            Car::updateOrCreate(
                ['license_plate' => $carData['license_plate']],
                $carData
            );
        }
    }
}
