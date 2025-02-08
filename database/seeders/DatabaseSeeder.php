<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         //User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::create([
            'name' => 'Rimon',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '01770108929',
            'password' => bcrypt('123456')
        ]);

        // Seed roles and permissions
        //$this->call(PermissionTableSeeder::class);

        // Create 5 demo users with roles
        User::factory()->count(5)->create()->each(function ($user) {
            if ($user->id == 2) {
                $user->assignRole('super-admin');
            } elseif ($user->id == 4) {
                $user->assignRole('admin');
            } else {
                $user->assignRole('user');
            }
        });
    }
}
