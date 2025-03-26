<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define role names to ensure they match
        $roleNames = ['admin', 'manager', 'cashier', 'inventory', 'staff'];

        // Create or get roles
        $roles = [];
        foreach ($roleNames as $roleName) {
            $roles[$roleName] = Role::firstOrCreate(['name' => $roleName]);
            $this->command->info("Role found or created: {$roleName}");
        }

        // Create John Doe (Admin)
        $john = User::create([
            'name'              => 'John Doe',
            'gender'            => 'male',
            'profile'           => 'A sample profile for John Doe.',
            'email'             => 'john.doe@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // Change password as needed
            'remember_token'    => Str::random(10),
            'roles_id'          => $roles['admin']->id,
        ]);

        // Create Jane Smith (Manager)
        $jane = User::create([
            'name'              => 'Jane Smith',
            'gender'            => 'female',
            'profile'           => 'A sample profile for Jane Smith.',
            'email'             => 'jane.smith@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'roles_id'          => $roles['manager']->id,
        ]);

        // Create Bob Johnson (Cashier)
        $bob = User::create([
            'name'              => 'Bob Johnson',
            'gender'            => 'male',
            'profile'           => 'A sample profile for Bob Johnson.',
            'email'             => 'bob.johnson@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'roles_id'          => $roles['cashier']->id,
        ]);

        // Create Sarah Lee (Inventory)
        $sarah = User::create([
            'name'              => 'Sarah Lee',
            'gender'            => 'female',
            'profile'           => 'A sample profile for Sarah Lee.',
            'email'             => 'sarah.lee@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'roles_id'          => $roles['inventory']->id,
        ]);

        // Create Mike Brown (Staff)
        $mike = User::create([
            'name'              => 'Mike Brown',
            'gender'            => 'male',
            'profile'           => 'A sample profile for Mike Brown.',
            'email'             => 'mike.brown@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'roles_id'          => $roles['staff']->id,
        ]);

        $this->command->info('Users created with various roles');
    }
}
