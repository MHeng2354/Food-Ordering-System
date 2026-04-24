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
        Food::create([
            'name' => 'Air Batu Campur',
            'description' => 'Refreshing shaved ice dessert with colorful syrup and condensed milk.',
            'price' => 5.0,
            'availability' => 'available',
            'image' => 'home/foods/abc.png',
            'category_id' => 5,  // Desserts
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Apam Balik',
            'description' => 'Sweet Malaysian pancake filled with peanuts, corn, and sugar.',
            'price' => 7.0,
            'availability' => 'available',
            'image' => 'home/foods/apam_balik.png',
            'category_id' => 3,  // Snacks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Kuih Lapis',
            'description' => 'Colorful layered Malaysian rice cake with rich coconut flavors.',
            'price' => 6.0,
            'availability' => 'available',
            'image' => 'home/foods/kuih_lapis.png',
            'category_id' => 5,  // Desserts
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Kuih Talam',
            'description' => 'Steamed coconut dessert with a creamy top layer and pandan-flavored base.',
            'price' => 5.0,
            'availability' => 'available',
            'image' => 'home/foods/kuih_talam.png',
            'category_id' => 5,  // desserts
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Milo',
            'description' => 'Classic chocolate malt beverage, best served hot or cold.',
            'price' => 3.5,
            'availability' => 'available',
            'image' => 'home/foods/milo.png',
            'category_id' => 4,  // Drinks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Milo Dinosaur',
            'description' => 'Cold milo topped with a generous layer of milo powder and evaporated milk.',
            'price' => 5.0,
            'availability' => 'available',
            'image' => 'home/foods/milo_dinosaur.png',
            'category_id' => 4,  // Drinks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Naan',
            'description' => 'Soft and fluffy Indian flatbread, perfect for dipping in curry or yogurt.',
            'price' => 6.0,
            'availability' => 'available',
            'image' => 'home/foods/naan.png',
            'category_id' => 3,  // Snacks
            'promotion_id' => 2,  // No Promotion
        ]);
        Food::create([
            'name' => 'Shawarma',
            'description' => 'Spiced Middle Eastern wrap with seasoned meat, lettuce, tomato, and tahini sauce.',
            'price' => 12.0,
            'availability' => 'available',
            'image' => 'home/foods/shawarma.png',
            'category_id' => 3,  // Snacks
            'promotion_id' => 2,  // No Promotion
        ]);
    }
}
