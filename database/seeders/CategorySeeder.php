<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Engine Parts',
                'slug' => 'engine-parts',
                'description' => 'High-quality engine components and parts for all motorcycle brands.'
            ],
            [
                'name' => 'Brake System',
                'slug' => 'brake-system',
                'description' => 'Brake pads, rotors, calipers, and brake fluids for safe stopping.'
            ],
            [
                'name' => 'Electrical Parts',
                'slug' => 'electrical-parts',
                'description' => 'Batteries, lights, switches, wiring harnesses, and electrical components.'
            ],
            [
                'name' => 'Suspension',
                'slug' => 'suspension',
                'description' => 'Forks, shocks, springs, and suspension components for smooth rides.'
            ],
            [
                'name' => 'Exhaust System',
                'slug' => 'exhaust-system',
                'description' => 'Mufflers, exhaust pipes, and complete exhaust systems.'
            ],
            [
                'name' => 'Body Parts',
                'slug' => 'body-parts',
                'description' => 'Fenders, fairings, seats, mirrors, and other body components.'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
        
        $this->command->info('Categories seeded successfully!');
    }
}