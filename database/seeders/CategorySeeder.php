<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Musik',
            'Teknologi',
            'Olahraga',
            'Seni & Budaya',
            'Bisnis',
            'Workshop'
        ];

        foreach ($categories as $catName) {
            Category::firstOrCreate(['name' => $catName]);
        }
    }
}
