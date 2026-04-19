<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Promotion::create([
            'name' => 'Hari Raya Sale',
            'description' => 'Special discount for Hari Raya celebration',
            'discount_percentage' => 15.0,
            'start_date' => '2026-04-01',
            'end_date' => '2026-05-31',
        ]);

        Promotion::create([
            'name' => 'No Promotion',
            'description' => 'No discount',
            'discount_percentage' => 0.0,
        ]);
    }
}
