<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ExportProductsSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        
        echo "Copy these products to your ProductSeeder:\n\n";
        echo "[\n";
        
        foreach ($products as $product) {
            echo "    [\n";
            echo "        'name' => '{$product->name}',\n";
            echo "        'slug' => '{$product->slug}',\n";
            echo "        'description' => '{$product->description}',\n";
            echo "        'price' => {$product->price},\n";
            echo "        'stock' => {$product->stock},\n";
            echo "        'category_id' => {$product->category_id},\n";
            echo "        'is_active' => " . ($product->is_active ? 'true' : 'false') . "\n";
            echo "    ],\n";
        }
        
        echo "];\n";
    }
}