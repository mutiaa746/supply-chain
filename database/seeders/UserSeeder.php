<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah user sudah ada, jika belum buat
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
        }
        
        // Buat user biasa
        if (!User::where('email', 'user@example.com')->exists()) {
            User::create([
                'name' => 'User',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
        }
        
        $this->command->info('User created successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User: user@example.com / password');
    }
}