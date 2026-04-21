<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $paymentMethod = $request->input('payment_method');
        
        if (!in_array($paymentMethod, ['credit_card', 'debit_card', 'ewallet', 'bank_transfer'])) {
            return redirect()->route('cart.checkout')->with('error', 'Invalid payment method selected.');
        }

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $request->input('total_price', 0),
                'status' => 'completed'
            ]);

            $foodIds = array_keys($cart);
            $foods = Food::with('promotion')->whereIn('id', $foodIds)->get()->keyBy('id');

            // Create order items
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
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice
                ]);
            }

            // Clear cart
            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            return redirect()->route('cart.checkout')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }
}
