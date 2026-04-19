<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Food::create([
            'name' => 'Nasi Lemak',
            'description' => 'Fragrant coconut rice served with sambal, fried chicken, boiled egg, and peanuts.',
            'price' => 12.0,
            'availability' => 'available',
            'image' => 'home/foods/nasi_lemak.jpg',
            'category_id' => 1,  // Rice
            'promotion_id' => 1,  // Hari Raya Sale
        ]);
        Food::create([
            'name' => 'Satay',
            'description' => 'Grilled skewers of marinated chicken or beef served with peanut sauce and ketupat.',
            'price' => 15.0,
            'availability' => 'available',
            'image' => 'home/foods/satay.jpg',
            'category_id' => 3,  // Snacks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Char Kway Teow',
            'description' => 'Stir-fried flat rice noodles with prawns, eggs, bean sprouts, and dark soy sauce.',
            'price' => 10.0,
            'availability' => 'available',
            'image' => 'home/foods/char_kway_teow.jpg',
            'category_id' => 2,  // Noodles
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Roti Canai',
            'description' => 'Flaky flatbread served with curry sauce, perfect for breakfast or anytime.',
            'price' => 8.0,
            'availability' => 'available',
            'image' => 'home/foods/roti_canai.jpg',
            'category_id' => 3,  // Snacks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Laksa',
            'description' => 'Spicy noodle soup with coconut milk, prawns, and bean sprouts.',
            'price' => 11.0,
            'availability' => 'available',
            'image' => 'home/foods/laksa.jpg',
            'category_id' => 2,  // Noodles
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Rendang',
            'description' => 'Slow-cooked beef in coconut milk and spices, served with rice.',
            'price' => 18.0,
            'availability' => 'available',
            'image' => 'home/foods/rendang.jpg',
            'category_id' => 1,  // Rice
            'promotion_id' => 1,  // Hari Raya Sale
        ]);
        Food::create([
            'name' => 'Mee Goreng',
            'description' => 'Fried noodles with vegetables, eggs, and sweet soy sauce.',
            'price' => 9.0,
            'availability' => 'available',
            'image' => 'home/foods/mee_goreng.jpg',
            'category_id' => 2,  // Noodles
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Teh Tarik',
            'description' => 'Pulled tea with condensed milk, a popular Malaysian drink.',
            'price' => 4.0,
            'availability' => 'available',
            'image' => 'home/foods/teh_tarik.jpg',
            'category_id' => 4,  // Drinks
            'promotion_id' => 2,  // No Promotion
        ]);
    }
}
