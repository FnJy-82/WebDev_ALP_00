<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Veritix',
            'email' => 'admin@veritix.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'identity_number' => '12341234',
            'phone_number' => '081234567890',
            'is_banned' => '0'
        ]);
    }
}
