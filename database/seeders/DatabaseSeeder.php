<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create or find the admin user and ensure it has the super_admin role
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'      => 'Admin User',
                'password'  => bcrypt('password'),
                'is_active' => true,
            ]
        );

        $user->syncRoles(['super_admin']);
    }
}
