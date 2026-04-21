<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Promotion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $foods = Food::latest()->take(5)->get();
        $promotions = Promotion::where('discount_percentage', '>', 0)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->has('foods')
            ->with('foods')
            ->take(3)
            ->get();

        return view('homepage', compact('foods', 'promotions'));
    }
}
