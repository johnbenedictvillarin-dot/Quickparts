<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Performance Air Filter',
                'slug' => 'performance-air-filter',
                'description' => 'High-flow air filter for improved engine performance',
                'price' => 29.99,
                'stock' => 50,
                'image' => 'air-filter.jpg',
                'category_id' => 1
            ],
            [
                'name' => 'Ceramic Brake Pads',
                'slug' => 'ceramic-brake-pads',
                'description' => 'Premium ceramic brake pads with excellent stopping power',
                'price' => 45.99,
                'stock' => 30,
                'image' => 'brake-pads.jpg',
                'category_id' => 2
            ],
            [
                'name' => 'LED Headlight Bulb',
                'slug' => 'led-headlight-bulb',
                'description' => 'Bright LED headlight with 5000 lumens output',
                'price' => 39.99,
                'stock' => 100,
                'image' => 'led-bulb.jpg',
                'category_id' => 3
            ],
            [
                'name' => 'Rear Shock Absorber',
                'slug' => 'rear-shock-absorber',
                'description' => 'Adjustable rear shock for better handling',
                'price' => 149.99,
                'stock' => 20,
                'image' => 'shock.jpg',
                'category_id' => 4
            ],
            [
                'name' => 'Slip-On Exhaust Muffler',
                'slug' => 'slip-on-exhaust',
                'description' => 'Deep sound performance exhaust system',
                'price' => 199.99,
                'stock' => 15,
                'image' => 'exhaust.jpg',
                'category_id' => 5
            ],
            [
                'name' => 'Motorcycle Seat Cover',
                'slug' => 'seat-cover',
                'description' => 'Comfortable gel seat cover for long rides',
                'price' => 34.99,
                'stock' => 40,
                'image' => 'seat-cover.jpg',
                'category_id' => 6
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

// In ProductSeeder.php
use Illuminate\Support\Facades\File;

// Copy images from seeder folder to storage
if (File::exists(database_path('seeders/product_images'))) {
    File::copyDirectory(
        database_path('seeders/product_images'),
        storage_path('app/public/products')
    );
}