<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    // -----------------------------------------------
    // ASSIGN — admin assigns a driver to an order
    // -----------------------------------------------
    public function assign(Request $request, $orderId)
    {
        // Make sure the user is an admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can assign drivers.',
            ], 403);
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        // Order must be to_ship before a driver is assigned
        if ($order->status !== 'to_ship') {
            return response()->json([
                'message' => 'Order must be marked as to_ship before assigning a driver.',
            ], 400);
        }

        // Make sure the assigned user is actually a driver
        $driver = User::find($request->driver_id);
        if ($driver->role !== 'driver') {
            return response()->json([
                'message' => 'The selected user is not a driver.',
            ], 400);
        }

        // Check if delivery already exists for this order
        if ($order->delivery) {
            return response()->json([
                'message' => 'A driver is already assigned to this order.',
            ], 400);
        }

        $delivery = Delivery::create([
            'order_id' => $order->id,
            'user_id'  => $request->driver_id,
            'status'   => 'assigned',
        ]);

        return response()->json([
            'message'  => 'Driver assigned successfully!',
            'delivery' => $delivery,
        ], 201);
    }

    // -----------------------------------------------
    // MY DELIVERIES — driver sees their assigned deliveries
    // -----------------------------------------------
    public function myDeliveries(Request $request)
    {
        if ($request->user()->role !== 'driver') {
            return response()->json([
                'message' => 'Only drivers can view deliveries.',
            ], 403);
        }

        $deliveries = Delivery::where('user_id', $request->user()->id)
            ->with('order.items', 'order.address')
            ->latest()
            ->get();

        return response()->json($deliveries);
    }

    // -----------------------------------------------
    // UPDATE STATUS — driver updates delivery status
    // -----------------------------------------------
    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'driver') {
            return response()->json([
                'message' => 'Only drivers can update delivery status.',
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:picked_up,delivered',
        ]);

        $delivery = Delivery::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$delivery) {
            return response()->json([
                'message' => 'Delivery not found.',
            ], 404);
        }

        // Update delivery status and timestamps
        if ($request->status === 'picked_up') {
            $delivery->update([
                'status'       => 'picked_up',
                'picked_up_at' => now(),
            ]);

            // Also update the order status to shipped
            $delivery->order->update(['status' => 'shipped']);
        }

        if ($request->status === 'delivered') {
            $delivery->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
            ]);

            // Also update the order status to delivered
            $delivery->order->update(['status' => 'delivered']);
        }

        return response()->json([
            'message'  => 'Delivery status updated!',
            'delivery' => $delivery,
        ]);
    }
}