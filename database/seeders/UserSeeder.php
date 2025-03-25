<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create John Doe
        $john = User::create([
            'name'              => 'John Doe',
            'status'            => 'active',
            'gender'            => 'male',
            'profile'           => 'A sample profile for John Doe.',
            'email'             => 'john.doe@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // Change password as needed
            'remember_token'    => Str::random(10),
        ]);

        // Assign the "admin" role to John Doe.
        $john->assignRole('admin');

        // Create Jane Smith
        $jane = User::create([
            'name'              => 'Jane Smith',
            'status'            => 'active',
            'gender'            => 'female',
            'profile'           => 'A sample profile for Jane Smith.',
            'email'             => 'jane.smith@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // Change password as needed
            'remember_token'    => Str::random(10),
        ]);

        // Assign the "admin" role to Jane Smith.
        $jane->assignRole('admin');

        // You can add more users similarly...
    }
}
