<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use App\Models\Category;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CARI USER ORGANIZER / ADMIN
        // Logic: Cari yang role organizer dulu, kalau gak ada cari admin, kalau gak ada buat baru.
        $user = User::where('role', 'organizer')->first() 
             ?? User::where('role', 'admin')->first();

        // Jaga-jaga kalau UserSeeder lupa dijalankan/gagal
        if (!$user) {
            $user = User::create([
                'name' => 'Emergency EO',
                'email' => 'emergency@eo.com',
                'password' => bcrypt('password'),
                'role' => 'organizer'
            ]);
        }

        // 2. CARI VENUE & KATEGORI (Ambil sembarang)
        $venue = Venue::first();
        if(!$venue) {
            $venue = Venue::create(['name' => 'Convention Hall', 'address' => 'Jakarta', 'capacity' => 5000]);
        }
        
        $category = Category::first();
        if(!$category) {
            $category = Category::create(['name' => 'Music', 'slug' => 'music']);
        }

        // 3. BUAT EVENT CONTOH
        Event::create([
            'title' => 'Coldplay Music of the Spheres',
            'description' => 'Konser megah tahun ini dengan teknologi gelang Xyloband terbaru.',
            'start_time' => now()->addDays(30), // 30 hari lagi
            'end_time' => now()->addDays(30)->addHours(4),
            'venue_id' => $venue->id,
            'user_id' => $user->id, // << INI YANG BIKIN ERROR TADI
            'category_id' => $category->id,
            'banner_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
            'price' => 1500000,
            'status' => 'published',
            'quota' => 500,
        ]);

        Event::create([
            'title' => 'Tech Startup Summit 2025',
            'description' => 'Berkumpulnya para founder startup se-Asia Tenggara.',
            'start_time' => now()->addDays(14),
            'end_time' => now()->addDays(14)->addHours(8),
            'venue_id' => $venue->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'banner_image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
            'price' => 75000,
            'status' => 'published',
            'quota' => 200,
        ]);
    }
}