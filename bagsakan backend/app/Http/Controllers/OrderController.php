<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // -----------------------------------------------
    // CHECKOUT — buyer places an order from their cart
    // -----------------------------------------------
    public function checkout(Request $request)
    {
        $request->validate([
            'address_id'     => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,gcash',
            'notes'          => 'nullable|string',
        ]);

        $user = $request->user();

        // Get the buyer's cart with items
        $cart = Cart::where('user_id', $user->id)
            ->with('items.product')
            ->first();

        // Make sure cart exists and is not empty
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Calculate the total amount
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // Create the order
        $order = Order::create([
            'user_id'        => $user->id,
            'address_id'     => $request->address_id,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'status'         => 'pending',
            'total_amount'   => $total,
            'notes'          => $request->notes,
        ]);

        // Create order items from cart items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product->id,
                'shop_id'      => $item->product->shop_id,
                'product_name' => $item->product->name,
                'price'        => $item->product->price,
                'quantity'     => $item->quantity,
                'subtotal'     => $item->product->price * $item->quantity,
            ]);

            // Reduce the stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear the cart after checkout
        $cart->items()->delete();

        // Load the order with its items
        $order->load('items');

        return response()->json([
            'message' => 'Order placed successfully!',
            'order'   => $order,
        ], 201);
    }

    // -----------------------------------------------
    // INDEX — buyer views all their orders
    // -----------------------------------------------
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items', 'address')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    // -----------------------------------------------
    // SHOW — buyer views one specific order
    // -----------------------------------------------
    public function show(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('items', 'address')
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json($order);
    }
}