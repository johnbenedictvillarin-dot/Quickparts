<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Engine Parts', 'slug' => 'engine-parts', 'description' => 'Engine components and parts'],
            ['name' => 'Brake System', 'slug' => 'brake-system', 'description' => 'Brake pads, rotors, and calipers'],
            ['name' => 'Electrical Parts', 'slug' => 'electrical-parts', 'description' => 'Batteries, lights, and wiring'],
            ['name' => 'Suspension', 'slug' => 'suspension', 'description' => 'Forks, shocks, and springs'],
            ['name' => 'Exhaust System', 'slug' => 'exhaust-system', 'description' => 'Mufflers and exhaust pipes'],
            ['name' => 'Body Parts', 'slug' => 'body-parts', 'description' => 'Fenders, fairings, and seats'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}