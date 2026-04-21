<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        if ($food->availability !== 'available') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This food is currently unavailable.'
                ], 422);
            }

            return redirect()->back()->with('error', 'This food is currently unavailable.');
        }

        $quantityToAdd = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = min(99, $cart[$id]['quantity'] + $quantityToAdd);
        } else {
            $cart[$id] = [
                'quantity' => min(99, $quantityToAdd),
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Food added to cart successfully!',
                'cartCount' => count($cart),
                'foodId' => (int) $id,
                'quantityAdded' => $quantityToAdd,
            ]);
        }

        return redirect()->back()->with('success', 'Food added to cart successfully!');
    }

    public function index()
    {
        $summary = $this->buildCartSummary();

        return view('cart.index', [
            'cartItems' => $summary['cartItems'],
            'subtotal' => $summary['subtotal'],
            'totalQuantity' => $summary['totalQuantity'],
        ]);
    }

    public function checkout()
    {
        $summary = $this->buildCartSummary();

        if ($summary['cartItems']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        return view('payment.index', [
            'cartItems' => $summary['cartItems'],
            'subtotal' => $summary['subtotal'],
            'totalQuantity' => $summary['totalQuantity'],
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Food removed from cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = min(99, max(1, (int) $request->input('quantity', 1)));
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    private function buildCartSummary(): array
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return [
                'cartItems' => collect(),
                'subtotal' => 0,
                'totalQuantity' => 0,
            ];
        }

        $foods = Food::with('category', 'promotion')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $cartItems = collect();
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($cart as $foodId => $item) {
            $food = $foods->get((int) $foodId);

            if (!$food) {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = $this->getFoodUnitPrice($food);
            $lineTotal = $unitPrice * $quantity;

            $cartItems->push([
                'food' => $food,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'has_promotion' => $food->promotion && $food->promotion->discount_percentage > 0,
            ]);

            $subtotal += $lineTotal;
            $totalQuantity += $quantity;
        }

        return [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'totalQuantity' => $totalQuantity,
        ];
    }

    private function getFoodUnitPrice(Food $food): float
    {
        if ($food->promotion && $food->promotion->discount_percentage > 0) {
            return (float) ($food->price * (1 - ($food->promotion->discount_percentage / 100)));
        }

        return (float) $food->price;
    }
}
