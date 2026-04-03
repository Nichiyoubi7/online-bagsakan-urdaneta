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
            ['name' => 'Tomato',        'sku' => 'SKU-001', 'category_id' => $vegetables?->id, 'price' => 20.00,  'original_price' => 25.00,  'stock' => 100, 'weight_kg' => 0.50, 'description' => 'Fresh red tomatoes perfect for cooking, salads, and sauces.'],
            ['name' => 'Eggplant',      'sku' => 'SKU-002', 'category_id' => $vegetables?->id, 'price' => 15.00,  'original_price' => 20.00,  'stock' => 45,  'weight_kg' => 0.50, 'description' => 'Fresh eggplants perfect for adobo, tortang talong and other Filipino dishes.'],
            ['name' => 'Bitter Gourd',  'sku' => 'SKU-003', 'category_id' => $vegetables?->id, 'price' => 18.00,  'original_price' => 22.00,  'stock' => 80,  'weight_kg' => 0.50, 'description' => 'Fresh bitter gourd perfect for pinakbet and other Filipino vegetable dishes.'],
            ['name' => 'Okra',          'sku' => 'SKU-004', 'category_id' => $vegetables?->id, 'price' => 12.00,  'original_price' => 15.00,  'stock' => 90,  'weight_kg' => 0.25, 'description' => 'Fresh okra perfect for sinigang and other Filipino dishes.'],
            ['name' => 'Sitaw',         'sku' => 'SKU-005', 'category_id' => $vegetables?->id, 'price' => 15.00,  'original_price' => 18.00,  'stock' => 85,  'weight_kg' => 0.25, 'description' => 'Fresh string beans perfect for adobo and ginisang sitaw.'],
            ['name' => 'Kangkong',      'sku' => 'SKU-006', 'category_id' => $vegetables?->id, 'price' => 10.00,  'original_price' => 12.00,  'stock' => 70,  'weight_kg' => 0.25, 'description' => 'Fresh water spinach perfect for adobong kangkong and sinigang.'],
            ['name' => 'Repolyo',       'sku' => 'SKU-007', 'category_id' => $vegetables?->id, 'price' => 25.00,  'original_price' => 30.00,  'stock' => 60,  'weight_kg' => 1.00, 'description' => 'Fresh cabbage perfect for soups, stir fry and salads.'],
            ['name' => 'Carrot',        'sku' => 'SKU-008', 'category_id' => $vegetables?->id, 'price' => 25.00,  'original_price' => 30.00,  'stock' => 75,  'weight_kg' => 0.50, 'description' => 'Fresh carrots perfect for soups, salads and juicing.'],
            ['name' => 'Potato',        'sku' => 'SKU-009', 'category_id' => $vegetables?->id, 'price' => 30.00,  'original_price' => 35.00,  'stock' => 80,  'weight_kg' => 0.50, 'description' => 'Fresh potatoes perfect for soups, frying and stews.'],
            ['name' => 'Sibuyas',       'sku' => 'SKU-010', 'category_id' => $vegetables?->id, 'price' => 60.00,  'original_price' => 75.00,  'stock' => 120, 'weight_kg' => 0.50, 'description' => 'White onions sold per 500g.'],
            ['name' => 'Bawang',        'sku' => 'SKU-011', 'category_id' => $vegetables?->id, 'price' => 80.00,  'original_price' => 90.00,  'stock' => 35,  'weight_kg' => 0.25, 'description' => 'Fresh garlic perfect for cooking all Filipino dishes.'],
            ['name' => 'Luya',          'sku' => 'SKU-012', 'category_id' => $vegetables?->id, 'price' => 40.00,  'original_price' => 50.00,  'stock' => 45,  'weight_kg' => 0.25, 'description' => 'Fresh ginger perfect for cooking and making tea.'],
            ['name' => 'Mais',          'sku' => 'SKU-013', 'category_id' => $vegetables?->id, 'price' => 15.00,  'original_price' => 20.00,  'stock' => 110, 'weight_kg' => 0.50, 'description' => 'Fresh sweet corn perfect for boiling and grilling.'],
            ['name' => 'Siling Haba',   'sku' => 'SKU-014', 'category_id' => $vegetables?->id, 'price' => 20.00,  'original_price' => 25.00,  'stock' => 88,  'weight_kg' => 0.25, 'description' => 'Fresh long green chili perfect for sinigang and other dishes.'],
            ['name' => 'Siling Labuyo', 'sku' => 'SKU-015', 'category_id' => $vegetables?->id, 'price' => 25.00,  'original_price' => 30.00,  'stock' => 66,  'weight_kg' => 0.25, 'description' => 'Fresh bird\'s eye chili for spicy Filipino dishes.'],
            ['name' => 'Upo',           'sku' => 'SKU-016', 'category_id' => $vegetables?->id, 'price' => 15.00,  'original_price' => 18.00,  'stock' => 72,  'weight_kg' => 1.00, 'description' => 'Fresh bottle gourd perfect for ginisang upo and soups.'],
            ['name' => 'Patola',        'sku' => 'SKU-017', 'category_id' => $vegetables?->id, 'price' => 12.00,  'original_price' => 15.00,  'stock' => 58,  'weight_kg' => 0.50, 'description' => 'Fresh sponge gourd perfect for sinigang and soups.'],
            ['name' => 'Sigarilyas',    'sku' => 'SKU-018', 'category_id' => $vegetables?->id, 'price' => 12.00,  'original_price' => 15.00,  'stock' => 62,  'weight_kg' => 0.25, 'description' => 'Fresh winged beans perfect for salads and stir fry.'],
            ['name' => 'Labanos',       'sku' => 'SKU-019', 'category_id' => $vegetables?->id, 'price' => 15.00,  'original_price' => 18.00,  'stock' => 55,  'weight_kg' => 0.50, 'description' => 'Fresh radish perfect for sinigang and pickles.'],
            ['name' => 'Gabi',          'sku' => 'SKU-020', 'category_id' => $vegetables?->id, 'price' => 20.00,  'original_price' => 25.00,  'stock' => 48,  'weight_kg' => 0.50, 'description' => 'Fresh taro perfect for sinigang and ginataan.'],
            ['name' => 'Kamote',        'sku' => 'SKU-021', 'category_id' => $vegetables?->id, 'price' => 18.00,  'original_price' => 22.00,  'stock' => 70,  'weight_kg' => 0.50, 'description' => 'Fresh sweet potato perfect for boiling and as side dish.'],

            // Fruits
            ['name' => 'Mango',         'sku' => 'SKU-022', 'category_id' => $fruits?->id, 'price' => 50.00,  'original_price' => 60.00,  'stock' => 100, 'weight_kg' => 1.00, 'description' => 'Sweet and juicy mangoes fresh from Pangasinan orchards.'],
            ['name' => 'Saging',        'sku' => 'SKU-023', 'category_id' => $fruits?->id, 'price' => 30.00,  'original_price' => 35.00,  'stock' => 90,  'weight_kg' => 1.00, 'description' => 'Fresh bananas perfect for snacking, cooking and making desserts.'],
            ['name' => 'Papaya',        'sku' => 'SKU-024', 'category_id' => $fruits?->id, 'price' => 40.00,  'original_price' => 50.00,  'stock' => 70,  'weight_kg' => 1.00, 'description' => 'Fresh papaya perfect for eating fresh or cooking as a vegetable when unripe.'],
            ['name' => 'Pakwan',        'sku' => 'SKU-025', 'category_id' => $fruits?->id, 'price' => 80.00,  'original_price' => 100.00, 'stock' => 40,  'weight_kg' => 3.00, 'description' => 'Fresh watermelon perfect for beating the heat and staying hydrated.'],
            ['name' => 'Melon',         'sku' => 'SKU-026', 'category_id' => $fruits?->id, 'price' => 70.00,  'original_price' => 85.00,  'stock' => 45,  'weight_kg' => 1.50, 'description' => 'Fresh melon sweet and juicy perfect for hot days.'],
            ['name' => 'Pineapple',     'sku' => 'SKU-027', 'category_id' => $fruits?->id, 'price' => 45.00,  'original_price' => 55.00,  'stock' => 60,  'weight_kg' => 1.00, 'description' => 'Fresh pineapple perfect for juicing, cooking and eating fresh.'],
            ['name' => 'Avocado',       'sku' => 'SKU-028', 'category_id' => $fruits?->id, 'price' => 60.00,  'original_price' => 75.00,  'stock' => 50,  'weight_kg' => 0.50, 'description' => 'Fresh avocado perfect for shakes, salads and spreads.'],
            ['name' => 'Guava',         'sku' => 'SKU-029', 'category_id' => $fruits?->id, 'price' => 25.00,  'original_price' => 30.00,  'stock' => 80,  'weight_kg' => 0.50, 'description' => 'Fresh guava perfect for eating fresh or making juice.'],
            ['name' => 'Rambutan',      'sku' => 'SKU-030', 'category_id' => $fruits?->id, 'price' => 35.00,  'original_price' => 45.00,  'stock' => 55,  'weight_kg' => 0.50, 'description' => 'Fresh rambutan sweet and juicy tropical fruit.'],
            ['name' => 'Lanzones',      'sku' => 'SKU-031', 'category_id' => $fruits?->id, 'price' => 40.00,  'original_price' => 50.00,  'stock' => 48,  'weight_kg' => 0.50, 'description' => 'Fresh lanzones sweet tropical fruit from local farms.'],
            ['name' => 'Calamansi',     'sku' => 'SKU-032', 'category_id' => $fruits?->id, 'price' => 20.00,  'original_price' => 25.00,  'stock' => 95,  'weight_kg' => 0.25, 'description' => 'Fresh calamansi perfect for juicing and cooking.'],
            ['name' => 'Orange',        'sku' => 'SKU-033', 'category_id' => $fruits?->id, 'price' => 35.00,  'original_price' => 40.00,  'stock' => 75,  'weight_kg' => 0.50, 'description' => 'Fresh oranges perfect for juicing and eating fresh.'],
            ['name' => 'Apple',         'sku' => 'SKU-034', 'category_id' => $fruits?->id, 'price' => 45.00,  'original_price' => 55.00,  'stock' => 65,  'weight_kg' => 0.50, 'description' => 'Fresh apples perfect for snacking and cooking.'],
            ['name' => 'Grapes',        'sku' => 'SKU-035', 'category_id' => $fruits?->id, 'price' => 80.00,  'original_price' => 95.00,  'stock' => 40,  'weight_kg' => 0.50, 'description' => 'Fresh grapes perfect for snacking and making juice.'],

            // Meat & Fish
            ['name' => 'Chicken',       'sku' => 'SKU-036', 'category_id' => $meat?->id, 'price' => 180.00, 'original_price' => 200.00, 'stock' => 50,  'weight_kg' => 1.00, 'description' => 'Fresh native chicken from local farms. Perfect for adobo, tinola and lechon manok.'],
            ['name' => 'Pork Meat',     'sku' => 'SKU-037', 'category_id' => $meat?->id, 'price' => 220.00, 'original_price' => 250.00, 'stock' => 40,  'weight_kg' => 1.00, 'description' => 'Fresh pork meat perfect for adobo, sinigang and other Filipino dishes.'],
            ['name' => 'Egg',           'sku' => 'SKU-038', 'category_id' => $meat?->id, 'price' => 12.00,  'original_price' => 14.00,  'stock' => 200, 'weight_kg' => 0.06, 'description' => 'Fresh native eggs from free-range chickens. Perfect for any dish.'],
            ['name' => 'Rice',          'sku' => 'SKU-039', 'category_id' => $meat?->id, 'price' => 55.00,  'original_price' => 60.00,  'stock' => 150, 'weight_kg' => 1.00, 'description' => 'Premium quality rice from local Pangasinan farms.'],
            ['name' => 'Bangus',        'sku' => 'SKU-040', 'category_id' => $meat?->id, 'price' => 150.00, 'original_price' => 180.00, 'stock' => 50,  'weight_kg' => 1.00, 'description' => 'Fresh boneless bangus per kilo.'],
            ['name' => 'Tilapia',       'sku' => 'SKU-041', 'category_id' => $meat?->id, 'price' => 120.00, 'original_price' => 140.00, 'stock' => 60,  'weight_kg' => 1.00, 'description' => 'Fresh tilapia per kilo.'],
            ['name' => 'Hipon',         'sku' => 'SKU-042', 'category_id' => $meat?->id, 'price' => 350.00, 'original_price' => 400.00, 'stock' => 25,  'weight_kg' => 0.50, 'description' => 'Fresh medium shrimp per 500g.'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                array_merge($product, ['user_id' => $seller->id, 'status' => 'active'])
            );
        }
    }
}