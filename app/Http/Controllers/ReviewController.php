<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //

    public function store(Request $request, $food_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = new \App\Models\Review();
        $review->user_id = auth()->id();
        $review->food_id = $food_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function destroy($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $this->authorize('delete', $review);

        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
