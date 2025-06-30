<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Doctor
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '123-456-7890',
            'specialization' => 'Cardiology',
        ]);
    }
}