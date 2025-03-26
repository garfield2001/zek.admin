<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@example.com',
                'phone_number' => '1234567890',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'phone_number' => '2345678901',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'first_name' => 'Staff',
                'last_name' => 'Member',
                'email' => 'staff@example.com',
                'phone_number' => '3456789012',
                'username' => 'staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'first_name' => 'Support',
                'last_name' => 'Staff',
                'email' => 'support@example.com',
                'phone_number' => '4567890123',
                'username' => 'support',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
