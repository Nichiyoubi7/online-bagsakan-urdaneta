<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@bagsakan.ph'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );
        $admin->assignRole('admin');

        // Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@bagsakan.ph'],
            [
                'name'     => 'Juan Staff',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );
        $staff->assignRole('staff');

        // Seller
        $seller = User::firstOrCreate(
            ['email' => 'seller@bagsakan.ph'],
            [
                'name'         => 'Mang Bert',
                'password'     => Hash::make('password'),
                'status'       => 'active',
                'store_name'   => "Mang Bert's Wet Market",
                'store_status' => 'open',
            ]
        );
        $seller->assignRole('seller');

        // Driver
        $driver = User::firstOrCreate(
            ['email' => 'driver@bagsakan.ph'],
            [
                'name'         => 'Rodel Mendoza',
                'password'     => Hash::make('password'),
                'status'       => 'active',
                'vehicle_type' => 'Motorcycle',
                'is_online'    => true,
            ]
        );
        $driver->assignRole('driver');

        // Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@bagsakan.ph'],
            [
                'name'     => 'Maria Santos',
                'password' => Hash::make('password'),
                'status'   => 'active',
                'address'  => 'Psu Executive Vlg, Urdaneta City',
            ]
        );
        $customer->assignRole('customer');
    }
}