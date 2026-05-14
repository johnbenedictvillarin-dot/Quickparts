<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $engineId = Category::where('slug', 'engine-parts')->first()->id;
        $brakeId = Category::where('slug', 'brake-system')->first()->id;
        $electricalId = Category::where('slug', 'electrical-parts')->first()->id;
        $suspensionId = Category::where('slug', 'suspension')->first()->id;
        $exhaustId = Category::where('slug', 'exhaust-system')->first()->id;
        $bodyId = Category::where('slug', 'body-parts')->first()->id;

        $products = [
            [
                'name' => 'JVT Pipe Version 3',
                'slug' => 'jvt-pipe-version-3',
                'description' => 'For Nmax/Aerox/Click/PCX/ADV/Beat/Mio',
                'price' => 5500,
                'rating' => 4.8,
                'review_count' => 234,
                'stock' => 6,
                'category_id' => $exhaustId,
                'image' => 'products/jvt-pipe-version-3.jpg',
                'is_active' => true
            ],
            [
                'name' => 'TSMP S2.5 Pipe (BLACK) 32mm',
                'slug' => 'tsmp-s25-black-32mm',
                'description' => 'For Nmax/Aerox/Click/PCX/ADV/Beat/Mio',
                'price' => 5800,
                'rating' => 4.9,
                'review_count' => 189,
                'stock' => 7,
                'category_id' => $exhaustId,
                'image' => 'products/tsmp-s25-black-32mm.jpg',
                'is_active' => true
            ],
            [
                'name' => 'MTRT Cylinder Block 59mm',
                'slug' => 'mtrt-cylinder-block-59mm',
                'description' => 'High performance cylinder block 59mm steel bore',
                'price' => 4200,
                'rating' => 4.7,
                'review_count' => 156,
                'stock' => 10,
                'category_id' => $engineId,
                'image' => 'products/mtrt-cylinder-block-59mm.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Brembo Brake Pad',
                'slug' => 'brembo-brake-pad',
                'description' => 'Premium ceramic brake pads for superior stopping power',
                'price' => 850,
                'rating' => 4.6,
                'review_count' => 342,
                'stock' => 14,
                'category_id' => $brakeId,
                'image' => 'products/brembo-brake-pad.png',
                'is_active' => true
            ],
            [
                'name' => 'YSS Shock 300mm Pair',
                'slug' => 'yss-shock-300mm-pair',
                'description' => 'Premium adjustable rear shocks 300mm',
                'price' => 5500,
                'rating' => 4.9,
                'review_count' => 98,
                'stock' => 5,
                'category_id' => $suspensionId,
                'image' => 'products/yss-shock-300mm-pair.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Brembo Caliper WR3 CNC',
                'slug' => 'brembo-caliper-wr3-cnc',
                'description' => 'CNC machined brake caliper with original bracket',
                'price' => 8500,
                'rating' => 4.9,
                'review_count' => 67,
                'stock' => 3,
                'category_id' => $brakeId,
                'image' => 'products/brembo-caliper-wr3-cnc.jpg',
                'is_active' => true
            ],
            [
                'name' => 'RCB Shock 300mm Pair',
                'slug' => 'rcb-shock-300mm-pair',
                'description' => 'Heavy duty adjustable shocks 300mm',
                'price' => 4800,
                'rating' => 4.5,
                'review_count' => 123,
                'stock' => 12,
                'category_id' => $suspensionId,
                'image' => 'products/rcb-shock-300mm-pair.jpg',
                'is_active' => true
            ],
            [
                'name' => 'ULDOG Cam Regrind',
                'slug' => 'uldog-cam-regrind',
                'description' => 'Stage 2 cam regrind 6.5 lift 255 duration 44 overlap',
                'price' => 2700,
                'rating' => 4.8,
                'review_count' => 45,
                'stock' => 5,
                'category_id' => $engineId,
                'image' => 'products/uldog-cam-regrind.jpg',
                'is_active' => true
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
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