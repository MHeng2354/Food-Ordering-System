<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    //

    public function index()
    {
        $foods = Food::with('category', 'reviews')->get();
        return view('foods.index', compact('foods'));
    }

    public function show($id)
    {
        $food = Food::with('category', 'reviews.user')->findOrFail($id);
        return view('foods.show', compact('food'));
    }

    public function promotions()
    {
        $promotions = \App\Models\Promotion::where('discount_percentage', '>', 0)
            ->has('foods')
            ->with('foods')
            ->get();

        return view('promotions', compact('promotions'));
    }

    public function showLatestFoods()
    {
        $foods = Food::latest()->take(5)->get();
        return view('homepage', compact('foods'));
    }
}
