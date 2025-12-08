<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AKUN ADMIN (Untuk Login Admin)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ticketpro.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. AKUN ORGANIZER (PENTING: Untuk Pemilik Event di EventSeeder)
        User::create([
            'name' => 'Event Organizer Utama',
            'email' => 'eo@ticketpro.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'email_verified_at' => now(),
        ]);

        // 3. AKUN CUSTOMER (Untuk Demo Beli Tiket)
        User::create([
            'name' => 'Budi Customer',
            'email' => 'customer@ticketpro.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
        
        // 4. Dummy Tambahan (Opsional)
        User::factory(5)->create(['role' => 'customer']);
    }
}
