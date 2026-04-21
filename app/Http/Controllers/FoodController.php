<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    //

    public function index(Request $request)
    {
        $query = Food::with('category', 'reviews', 'promotion');
        
        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $foods = $query->get();
        $categories = Category::all();
        $selectedCategory = $request->get('category_id');
        
        return view('foods.index', compact('foods', 'categories', 'selectedCategory'));
    }

    public function show($id)
    {
        $food = Food::with('category', 'reviews.user')->findOrFail($id);
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
