<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Food::class);

        $query = Food::with('category', 'promotion');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($query) use ($term) {
                $query
                    ->where('name', 'like', "%{$term}%")
                    ->orWhereHas('category', function ($query) use ($term) {
                        $query->where('name', 'like', "%{$term}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $foods = $query->get();
        $categories = \App\Models\Category::all();

        return view('admin.foods.index', compact('foods', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->authorize('create', Food::class);

        $categories = \App\Models\Category::all();
        $promotions = \App\Models\Promotion::all();
        return view('admin.foods.create', compact('categories', 'promotions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', Food::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'availability' => 'required|in:available,unavailable',
            'category_id' => 'required|exists:categories,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $food = new Food($data);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $food->image = $filename;
        }
        $food->save();
        return redirect()->route('admin.foods.index')->with('success', 'Food created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $food = Food::findOrFail($id);
        $this->authorize('view', $food);

        return view('admin.foods.show', compact('food'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $food = Food::findOrFail($id);
        $this->authorize('update', $food);

        $categories = \App\Models\Category::all();
        $promotions = \App\Models\Promotion::all();
        return view('admin.foods.edit', compact('food', 'categories', 'promotions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $food = Food::findOrFail($id);
        $this->authorize('update', $food);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'availability' => 'required|in:available,unavailable',
            'category_id' => 'required|exists:categories,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $food->fill($data);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $food->image = $filename;
        }
        $food->save();
        return redirect()->route('admin.foods.index')->with('success', 'Food updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $food = Food::findOrFail($id);
        $this->authorize('delete', $food);

        $food->delete();
        return redirect()->route('admin.foods.index')->with('success', 'Food deleted successfully.');
    }
}
