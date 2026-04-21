<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //

    public function add(Request $request, $id){
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
        if(isset($cart[$id])){
            $cart[$id]['quantity'] += $quantityToAdd;
        } else {
            $cart[$id] = [
                "quantity" => $quantityToAdd
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

    public function index(){
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return view('cart.index', [
                'cartItems' => collect(),
                'subtotal' => 0,
                'totalQuantity' => 0,
            ]);
        }

        $foodIds = array_keys($cart);
        $foods = Food::with('category', 'promotion')->whereIn('id', $foodIds)->get()->keyBy('id');

        $cartItems = collect();
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($cart as $foodId => $item) {
            $food = $foods->get((int) $foodId);

            if (!$food) {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $hasPromotion = $food->promotion && $food->promotion->discount_percentage > 0;
            $unitPrice = $hasPromotion
                ? $food->price * (1 - ($food->promotion->discount_percentage / 100))
                : $food->price;
            $lineTotal = $unitPrice * $quantity;

            $subtotal += $lineTotal;
            $totalQuantity += $quantity;

            $cartItems->push([
                'food' => $food,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'has_promotion' => $hasPromotion,
            ]);
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'totalQuantity'));
    }

    public function checkout(){
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        $foodIds = array_keys($cart);
        $foods = Food::with('category', 'promotion')->whereIn('id', $foodIds)->get()->keyBy('id');

        $cartItems = collect();
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($cart as $foodId => $item) {
            $food = $foods->get((int) $foodId);

            if (!$food) {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $hasPromotion = $food->promotion && $food->promotion->discount_percentage > 0;
            $unitPrice = $hasPromotion
                ? $food->price * (1 - ($food->promotion->discount_percentage / 100))
                : $food->price;
            $lineTotal = $unitPrice * $quantity;

            $subtotal += $lineTotal;
            $totalQuantity += $quantity;

            $cartItems->push([
                'food' => $food,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'has_promotion' => $hasPromotion,
            ]);
        }

        return view('payment.index', compact('cartItems', 'subtotal', 'totalQuantity'));
    }

    public function remove($id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Food removed from cart successfully!');
    }

    public function update(Request $request, $id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){
            $cart[$id]['quantity'] = max(1, (int) $request->input('quantity', 1));
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
}
