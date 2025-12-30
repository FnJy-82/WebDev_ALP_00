<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use App\Models\Category;
use App\Models\TicketCategory; // Don't forget this
use App\Models\Ticket;         // Don't forget this

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CARI USER ORGANIZER / ADMIN
        $user = User::where('role', 'eo')->first()
            ?? User::where('role', 'admin')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Emergency EO',
                'email' => 'emergency@eo.com',
                'password' => bcrypt('password'),
                'role' => 'eo'
            ]);
        }

        // 2. CARI VENUE
        $venue = Venue::first();
        if(!$venue) {
            $venue = Venue::create(['name' => 'Convention Hall', 'address' => 'Jakarta', 'capacity' => 5000]);
        }

        // 3. AMBIL KATEGORI GENRE (Musik/Tekno)
        $catMusik = Category::where('name', 'Musik')->first();
        $catTekno = Category::where('name', 'Teknologi')->first();

        if (!$catMusik) $catMusik = Category::create(['name' => 'Musik']);
        if (!$catTekno) $catTekno = Category::create(['name' => 'Teknologi']);

        // ==========================================
        // EVENT 1: COLDPLAY (With VIP & Festival Seats)
        // ==========================================
        $event1 = Event::create([
            'title' => 'Coldplay: Music of the Spheres',
            'description' => 'Konser megah tahun ini dengan teknologi gelang Xyloband terbaru.',
            'start_time' => now()->addDays(30)->setTime(19, 0),
            'end_time' => now()->addDays(30)->setTime(23, 0),
            'venue_id' => $venue->id,
            'user_id' => $user->id,
            'banner_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
            'price' => 1500000, // Display price only
            'status' => 'active',
            'quota' => 500,
        ]);

        $event1->categories()->attach($catMusik->id);

        // --- Create Section 1: VIP A (Expensive) ---
        $vipCategory = TicketCategory::create([
            'event_id' => $event1->id,
            'name' => 'VIP A',
            'price' => 3000000,
            'total_quota' => 50
        ]);
        $this->generateSeats($event1->id, $vipCategory->id, 5, 10, 'A'); // 5 Rows, 10 Cols starting at Row A

        // --- Create Section 2: Festival (Cheaper) ---
        $festCategory = TicketCategory::create([
            'event_id' => $event1->id,
            'name' => 'Festival',
            'price' => 1500000,
            'total_quota' => 100
        ]);
        $this->generateSeats($event1->id, $festCategory->id, 5, 20, 'F'); // 5 Rows, 20 Cols starting at Row F


        // ==========================================
        // EVENT 2: TECH SUMMIT (With General Seating)
        // ==========================================
        $event2 = Event::create([
            'title' => 'Global Tech Startup Summit 2025',
            'description' => 'Berkumpulnya para founder startup, investor, dan tech enthusiast.',
            'start_time' => now()->addDays(14)->setTime(9, 0),
            'end_time' => now()->addDays(14)->setTime(17, 0),
            'venue_id' => $venue->id,
            'user_id' => $user->id,
            'banner_image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
            'price' => 75000,
            'status' => 'active',
            'quota' => 200,
        ]);

        $event2->categories()->attach($catTekno->id);

        // --- Create Section: General Admission ---
        $genCategory = TicketCategory::create([
            'event_id' => $event2->id,
            'name' => 'General Admission',
            'price' => 75000,
            'total_quota' => 100
        ]);
        $this->generateSeats($event2->id, $genCategory->id, 10, 10, 'A'); // 10 Rows, 10 Cols
    }

    /**
     * Helper function to generate seats loop
     */
    private function generateSeats($eventId, $categoryId, $rows, $cols, $startRowChar)
    {
        $startAscii = ord($startRowChar); // Convert char 'A' to ASCII number 65

        for ($i = 0; $i < $rows; $i++) {
            $rowLabel = chr($startAscii + $i); // A, B, C...

            for ($j = 1; $j <= $cols; $j++) {
                Ticket::create([
                    'event_id' => $eventId,
                    'category_id' => $categoryId, // Important for checkout price!
                    'seat_number' => $rowLabel . '-' . $j,
                    'row_label' => $rowLabel, // Grouping for View
                    'status' => 'available',
                ]);
            }
        }
    }
}
