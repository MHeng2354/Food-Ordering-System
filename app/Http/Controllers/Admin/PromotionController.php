<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Promotion::class);

        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $this->authorize('create', Promotion::class);

        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Promotion::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/promo'), $imageName);
            $data['image'] = 'promo/' . $imageName;
        }

        Promotion::create($data);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        $this->authorize('update', $promotion);

        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $this->authorize('update', $promotion);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($promotion->image && file_exists(public_path('images/' . $promotion->image))) {
                unlink(public_path('images/' . $promotion->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/promo'), $imageName);
            $data['image'] = 'promo/' . $imageName;
        }

        $promotion->update($data);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $this->authorize('delete', $promotion);

        // Delete image if exists
        if ($promotion->image && file_exists(public_path('images/' . $promotion->image))) {
            unlink(public_path('images/' . $promotion->image));
        }

        $promotion->delete();

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
