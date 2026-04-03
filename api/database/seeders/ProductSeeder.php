<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'seller@bagsakan.ph')->first();
        $vegetables = Category::where('slug', 'vegetables')->first();
        $fruits = Category::where('slug', 'fruits')->first();
        $meat = Category::where('slug', 'meat-fish')->first();
        $products = [
            // Vegetables
            ['name' => 'Ampalaya (Bitter Gourd)', 'sku' => 'VEG-001', 'category_id' => $vegetables?->id, 'price' => 45.00, 'original_price' => 55.00, 'stock' => 100, 'weight_kg' => 0.50, 'description' => 'Fresh bitter gourd from local farms.'],
            ['name' => 'Sitaw (String Beans)', 'sku' => 'VEG-002', 'category_id' => $vegetables?->id, 'price' => 35.00, 'original_price' => 45.00, 'stock' => 80, 'weight_kg' => 0.50, 'description' => 'Fresh string beans harvested daily.'],
            ['name' => 'Kamatis (Tomato)', 'sku' => 'VEG-003', 'category_id' => $vegetables?->id, 'price' => 30.00, 'original_price' => 40.00, 'stock' => 150, 'weight_kg' => 0.50, 'description' => 'Ripe red tomatoes, perfect for cooking.'],
            ['name' => 'Talong (Eggplant)', 'sku' => 'VEG-004', 'category_id' => $vegetables?->id, 'price' => 40.00, 'original_price' => 50.00, 'stock' => 90, 'weight_kg' => 0.50, 'description' => 'Fresh eggplant from Urdaneta farms.'],
            ['name' => 'Sibuyas (Onion)', 'sku' => 'VEG-005', 'category_id' => $vegetables?->id, 'price' => 60.00, 'original_price' => 75.00, 'stock' => 200, 'weight_kg' => 0.50, 'description' => 'White onions, sold per 500g.'],
            // Fruits
            ['name' => 'Saging (Banana)', 'sku' => 'FRT-001', 'category_id' => $fruits?->id, 'price' => 50.00, 'original_price' => 65.00, 'stock' => 120, 'weight_kg' => 1.00, 'description' => 'Sweet lakatan bananas per kilo.'],
            ['name' => 'Mangga (Mango)', 'sku' => 'FRT-002', 'category_id' => $fruits?->id, 'price' => 80.00, 'original_price' => 100.00, 'stock' => 100, 'weight_kg' => 1.00, 'description' => 'Sweet carabao mangoes per kilo.'],
            ['name' => 'Papaya', 'sku' => 'FRT-003', 'category_id' => $fruits?->id, 'price' => 35.00, 'original_price' => 45.00, 'stock' => 60, 'weight_kg' => 1.00, 'description' => 'Fresh ripe papaya per kilo.'],
            ['name' => 'Lansones', 'sku' => 'FRT-004', 'category_id' => $fruits?->id, 'price' => 90.00, 'original_price' => 110.00, 'stock' => 50, 'weight_kg' => 1.00, 'description' => 'Sweet lansones per kilo.'],
            ['name' => 'Pakwan (Watermelon)', 'sku' => 'FRT-005', 'category_id' => $fruits?->id, 'price' => 25.00, 'original_price' => 35.00, 'stock' => 40, 'weight_kg' => 2.00, 'description' => 'Fresh watermelon per kilo.'],
            // Meat & Fish
            ['name' => 'Bangus (Milkfish)', 'sku' => 'MEA-001', 'category_id' => $meat?->id, 'price' => 150.00, 'original_price' => 180.00, 'stock' => 50, 'weight_kg' => 1.00, 'description' => 'Fresh boneless bangus per kilo.'],
            ['name' => 'Tilapia', 'sku' => 'MEA-002', 'category_id' => $meat?->id, 'price' => 120.00, 'original_price' => 140.00, 'stock' => 60, 'weight_kg' => 1.00, 'description' => 'Fresh tilapia per kilo.'],
            ['name' => 'Baboy (Pork Liempo)', 'sku' => 'MEA-003', 'category_id' => $meat?->id, 'price' => 280.00, 'original_price' => 320.00, 'stock' => 40, 'weight_kg' => 1.00, 'description' => 'Fresh pork belly per kilo.'],
            ['name' => 'Manok (Whole Chicken)', 'sku' => 'MEA-004', 'category_id' => $meat?->id, 'price' => 220.00, 'original_price' => 260.00, 'stock' => 30, 'weight_kg' => 1.00, 'description' => 'Fresh whole chicken per kilo.'],
            ['name' => 'Hipon (Shrimp)', 'sku' => 'MEA-005', 'category_id' => $meat?->id, 'price' => 350.00, 'original_price' => 400.00, 'stock' => 25, 'weight_kg' => 0.50, 'description' => 'Fresh medium shrimp per 500g.'],
        ];
        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                array_merge($product, ['user_id' => $seller->id, 'status' => 'active'])
            );
        }
    }
}
