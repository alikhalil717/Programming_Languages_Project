<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // كلمة مرور قوية
            'phone_number' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
