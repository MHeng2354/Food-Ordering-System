<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min=1',
        ]);

        $order = new Order();
        $order->user_id = auth()->id();
        $order->save();

        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->food_id = $request->food_id;
        $orderItem->quantity = $request->quantity;
        $orderItem->price = Food::find($request->food_id)->price;  // Get current price
        $orderItem->save();

        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
    }

    public function show($id)
    {
        $order = Order::with('orderItems.food.category')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::with('orderItems.food.category')->where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }
}
