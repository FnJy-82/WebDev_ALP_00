<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil Data Referensi dari Database
        $organizer = User::where('email', 'admin@veritix.com')->first();

        // Pastikan organizer ada sebelum lanjut
        if (!$organizer) {
            $this->command->error('User Admin tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $venue1 = Venue::where('name', 'Grand City Convention Hall')->first();
        $venue2 = Venue::where('name', 'Gelora Bung Karno')->first();
        $venue3 = Venue::where('name', 'Ciputra World Ballroom')->first();

        // Helper function untuk ambil ID kategori berdasarkan nama
        $getCatId = fn($name) => Category::where('name', $name)->value('id');

        // 2. Buat Dummy Events~

        // --- Event 1: Konser Musik ---
        if ($venue1) {
            $event1 = Event::firstOrCreate(['title' => 'Surabaya Jazz Festival 2025'], [
                'user_id' => $organizer->id,
                'venue_id' => $venue1->id,
                'description' => 'Nikmati malam penuh alunan jazz dari musisi papan atas Indonesia dan Internasional.',
                'start_time' => Carbon::parse('2025-10-15 18:00:00'),
                'end_time' => Carbon::parse('2025-10-15 23:00:00'),
                'banner_image' => 'https://images.unsplash.com/photo-1514525253440-b393452e3383?w=800&q=80',
                'status' => 'active',
            ]);
            // Sync Kategori (Musik & Seni) - sync() lebih aman daripada attach() untuk seeder agar tidak duplikat
            $event1->categories()->sync([$getCatId('Musik'), $getCatId('Seni & Budaya')]);
        }

        // --- Event 2: Tech Conference ---
        if ($venue3) {
            $event2 = Event::firstOrCreate(['title' => 'Laravel Developer Summit'], [
                'user_id' => $organizer->id,
                'venue_id' => $venue3->id,
                'description' => 'Konferensi tahunan untuk para pengembang Laravel. Belajar fitur terbaru Laravel 11.',
                'start_time' => Carbon::parse('2025-11-20 09:00:00'),
                'end_time' => Carbon::parse('2025-11-20 17:00:00'),
                'banner_image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&q=80',
                'status' => 'active',
            ]);
            $event2->categories()->sync([$getCatId('Teknologi'), $getCatId('Workshop')]);
        }

        // --- Event 3: Bola ---
        if ($venue2) {
            $event3 = Event::firstOrCreate(['title' => 'Timnas Indonesia vs Argentina'], [
                'user_id' => $organizer->id,
                'venue_id' => $venue2->id,
                'description' => 'Pertandingan persahabatan FIFA Matchday yang paling ditunggu tahun ini.',
                'start_time' => Carbon::parse('2025-06-19 19:30:00'),
                'end_time' => Carbon::parse('2025-06-19 22:00:00'),
                'banner_image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&q=80',
                'status' => 'active',
            ]);
            $event3->categories()->sync([$getCatId('Olahraga')]);
        }
    }
}
