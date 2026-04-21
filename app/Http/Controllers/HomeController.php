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

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you could send an email, save to database, etc.
        // For now, just redirect with success message

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
