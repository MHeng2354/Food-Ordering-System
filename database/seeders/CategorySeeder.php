<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Rice']);
        Category::create(['name' => 'Noodles']);
        Category::create(['name' => 'Snacks']);
        Category::create(['name' => 'Drinks']);
        Category::create(['name' => 'Desserts']);
    }
}
