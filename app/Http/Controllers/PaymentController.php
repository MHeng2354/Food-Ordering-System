<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,ewallet,bank_transfer',
        ]);

        if (empty($validated['payment_method'])) {
            return redirect()->route('cart.checkout')->with('error', 'Invalid payment method selected.');
        }

        try {
            $foods = Food::with('promotion')
                ->whereIn('id', array_keys($cart))
                ->get()
                ->keyBy('id');

            $orderLines = [];
            $primaryFoodId = null;
            $totalQuantity = 0;
            $totalPrice = 0;

            foreach ($cart as $foodId => $cartRow) {
                $food = $foods->get((int) $foodId);

                if (!$food) {
                    continue;
                }

                $quantity = max(1, (int) ($cartRow['quantity'] ?? 1));
                $unitPrice = $this->resolveUnitPrice($food);

                $orderLines[] = [
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                ];

                if ($primaryFoodId === null) {
                    $primaryFoodId = $food->id;
                }

                $totalQuantity += $quantity;
                $totalPrice += ($unitPrice * $quantity);
            }

            if (empty($orderLines) || !$primaryFoodId) {
                return redirect()->route('cart.checkout')->with('error', 'Unable to create payment: no valid food found in cart.');
            }

            $order = DB::transaction(function () use ($primaryFoodId, $totalQuantity, $totalPrice, $orderLines) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'food_id' => $primaryFoodId,
                    'quantity' => $totalQuantity,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                ]);

                foreach ($orderLines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'food_id' => $line['food_id'],
                        'quantity' => $line['quantity'],
                        'price' => $line['price'],
                    ]);
                }

                return $order;
            });

            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            return redirect()->route('cart.checkout')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    private function resolveUnitPrice(Food $food): float
    {
        if ($food->promotion && $food->promotion->discount_percentage > 0) {
            return (float) ($food->price * (1 - ($food->promotion->discount_percentage / 100)));
        }

        return (float) $food->price;
    }
}
