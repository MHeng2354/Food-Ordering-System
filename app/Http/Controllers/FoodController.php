<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    //

    public function index(Request $request)
    {
        $foods = Food::with('category', 'promotion')->get();
        $categories = Category::all();

        // Group foods by category
        $foodsByCategory = $foods->groupBy(function ($food) {
            return $food->category ? $food->category->name : 'Uncategorized';
        });

        return view('foods.index', compact('foodsByCategory', 'categories'));
    }

    public function show($id)
    {
        $food = Food::with('category', 'promotion')->findOrFail($id);
        return view('foods.show', compact('food'));
    }

    public function promotions()
    {
        $promotions = \App\Models\Promotion::where('discount_percentage', '>', 0)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->has('foods')
            ->with('foods')
            ->get();

        return view('promotions', compact('promotions'));
    }

    public function toggleAvailability(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Toggle availability
        $food->availability = $food->availability === 'available' ? 'unavailable' : 'available';
        $food->save();

        return response()->json(['success' => true, 'availability' => $food->availability]);
    }

    public function showLatestFoods()
    {
        $foods = Food::latest()->take(5)->get();
        $promotions = \App\Models\Promotion::where('discount_percentage', '>', 0)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->has('foods')
            ->with('foods')
            ->take(3)
            ->get();
        return view('homepage', compact('foods', 'promotions'));
    }
}
