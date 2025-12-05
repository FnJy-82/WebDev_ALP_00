<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Venue::firstOrCreate(['name' => 'Grand City Convention Hall'], [
            'address' => 'Jl. Walikota Mustajab No.1',
            'city' => 'Surabaya',
            'capacity' => 5000,
            'layout_image_url' => null
        ]);

        Venue::firstOrCreate(['name' => 'Gelora Bung Karno'], [
            'address' => 'Jl. Pintu Satu Senayan',
            'city' => 'Jakarta',
            'capacity' => 77000,
            'layout_image_url' => null
        ]);

        Venue::firstOrCreate(['name' => 'Ciputra World Ballroom'], [
            'address' => 'Jl. Mayjen Sungkono No.89',
            'city' => 'Surabaya',
            'capacity' => 1500,
            'layout_image_url' => null
        ]);
    }
}
