<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan Anda mengimpor model User
use Illuminate\Support\Facades\Hash; // Untuk hashing password

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user Admin jika belum ada
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin Utama',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Ganti 'password' dengan password yang kuat di produksi
                'role' => 'admin',
                'email_verified_at' => now(), // Tandai email sudah diverifikasi
            ]);
            $this->command->info('Admin user created!');
        } else {
            $this->command->info('Admin user already exists.');
        }

        // Buat user Teller jika belum ada
        if (!User::where('email', 'teller@example.com')->exists()) {
            User::create([
                'name' => 'Teller Satu',
                'email' => 'teller@example.com',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Teller user created!');
        } else {
            $this->command->info('Teller user already exists.');
        }

        // Buat user Kabag jika belum ada
        if (!User::where('email', 'kabag@example.com')->exists()) {
            User::create([
                'name' => 'Kepala Bagian',
                'email' => 'kabag@example.com',
                'password' => Hash::make('password'),
                'role' => 'kabag',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Kabag user created!');
        } else {
            $this->command->info('Kabag user already exists.');
        }

        // Buat user Direksi jika belum ada
        if (!User::where('email', 'direksi@example.com')->exists()) {
            User::create([
                'name' => 'Direksi Utama',
                'email' => 'direksi@example.com',
                'password' => Hash::make('password'),
                'role' => 'direksi',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Direksi user created!');
        } else {
            $this->command->info('Direksi user already exists.');
        }
    }
}