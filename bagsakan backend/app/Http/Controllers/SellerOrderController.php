<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class SellerOrderController extends Controller
{
    // -----------------------------------------------
    // INDEX — seller views all orders containing
    //         products from their shop
    // -----------------------------------------------
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json([
                'message' => 'You do not have a shop.',
            ], 404);
        }

        // Find all order items that belong to this shop
        // then get the unique orders from those items
        $orderIds = OrderItem::where('shop_id', $shop->id)
            ->pluck('order_id')
            ->unique();

        $orders = Order::whereIn('id', $orderIds)
            ->with('items', 'address', 'user')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    // -----------------------------------------------
    // UPDATE STATUS — seller marks order as to_ship
    // -----------------------------------------------
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:to_ship,cancelled',
        ]);

        $shop = $request->user()->shop;

        // Make sure this order contains items from this seller
        $orderItem = OrderItem::where('shop_id', $shop->id)
            ->where('order_id', $id)
            ->first();

        if (!$orderItem) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        $order = Order::find($id);

        // Seller can only move from pending → to_ship
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'You can only update orders that are still pending.',
            ], 400);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status updated!',
            'order'   => $order,
        ]);
    }
}