<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Order::with(['items', 'customer', 'seller', 'driver']);

        if ($user->hasRole('customer')) {
            $query->where('customer_id', $user->id);
        } elseif ($user->hasRole('seller')) {
            $query->where('seller_id', $user->id);
        } elseif ($user->hasRole('driver')) {
            $query->where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'ready')->whereNull('driver_id');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product', 'customer', 'seller', 'driver'])->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_id'          => 'required|exists:users,id',
            'delivery_type'      => 'required|in:delivery,pickup',
            'payment_method'     => 'required|in:gcash,cod',
            'delivery_address'   => 'nullable|string',
            'delivery_note'      => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $subtotal    = 0;
        $totalWeight = 0;
        $orderItems  = [];

        foreach ($request->items as $item) {
            $product      = Product::findOrFail($item['product_id']);
            $itemSubtotal = $product->price * $item['quantity'];
            $subtotal    += $itemSubtotal;
            $totalWeight += ($product->weight_kg ?? 0) * $item['quantity'];
            $orderItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
                'quantity'     => $item['quantity'],
                'subtotal'     => $itemSubtotal,
            ];
        }

        $deliveryFee = $request->delivery_type === 'delivery' ? 50 : 0;
        $tip         = $request->tip ?? 0;
        $total       = $subtotal + $deliveryFee + $tip;

        $order = Order::create([
            'customer_id'      => $request->user()->id,
            'seller_id'        => $request->seller_id,
            'status'           => 'pending',
            'delivery_type'    => $request->delivery_type,
            'payment_method'   => $request->payment_method,
            'subtotal'         => $subtotal,
            'delivery_fee'     => $deliveryFee,
            'tip'              => $tip,
            'total'            => $total,
            'total_weight_kg'  => $totalWeight,
            'delivery_address' => $request->delivery_address,
            'delivery_note'    => $request->delivery_note,
        ]);

        $order->items()->createMany($orderItems);

        // Notify seller of new order
        Notification::create([
            'user_id'  => $request->seller_id,
            'title'    => 'New Order Received! 🛍️',
            'message'  => "Order #{$order->id} from {$request->user()->name} — ₱{$total}",
            'type'     => 'info',
            'icon'     => '🛍️',
            'order_id' => $order->id,
        ]);

        // Deduct stock and send alerts
        foreach ($request->items as $item) {
            $product  = Product::findOrFail($item['product_id']);
            $newStock = max(0, $product->stock - $item['quantity']);
            $product->update(['stock' => $newStock]);

            if ($newStock === 0) {
                Notification::create([
                    'user_id' => $product->user_id,
                    'title'   => 'Product Out of Stock ⚠️',
                    'message' => "Your product \"{$product->name}\" is now out of stock after Order #{$order->id}.",
                    'type'    => 'warning',
                    'icon'    => '⚠️',
                ]);
            } elseif ($newStock <= 5) {
                Notification::create([
                    'user_id' => $product->user_id,
                    'title'   => 'Low Stock Warning 📉',
                    'message' => "Your product \"{$product->name}\" only has {$newStock} left after Order #{$order->id}.",
                    'type'    => 'warning',
                    'icon'    => '📉',
                ]);
            }
        }

        // Audit log — order placed
        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'order.placed',
            'model_type' => 'Order',
            'model_id'   => $order->id,
            'new_values' => [
                'order_id'       => $order->id,
                'payment_method' => $order->payment_method,
                'total'          => $order->total,
                'status'         => 'pending',
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Order placed successfully!',
            'order'   => $order->load('items'),
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,in_transit,delivered,cancelled',
        ]);

        $order     = Order::with(['customer', 'seller'])->findOrFail($id);
        $oldStatus = $order->status;
        $data      = ['status' => $request->status];
        $status    = $request->status;

        if ($status === 'in_transit') {
            $data['driver_id'] = $request->user()->id;
        }
        if ($status === 'delivered') {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        $orderId    = $order->id;
        $sellerName = $order->seller->name ?? 'Seller';

        // Audit log — status change
        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'order.status_changed',
            'model_type' => 'Order',
            'model_id'   => $orderId,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $status],
            'ip_address' => $request->ip(),
        ]);

        match ($status) {
            'confirmed' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Confirmed! ✅',
                'message'  => "Your order #{$orderId} has been accepted by {$sellerName}.",
                'type'     => 'success',
                'icon'     => '✅',
                'order_id' => $orderId,
            ]),
            'preparing' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Being Prepared 👨‍🍳',
                'message'  => "Your order #{$orderId} is now being prepared.",
                'type'     => 'info',
                'icon'     => '👨‍🍳',
                'order_id' => $orderId,
            ]),
            'ready' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Ready! 📦',
                'message'  => "Your order #{$orderId} is ready and waiting for a driver.",
                'type'     => 'success',
                'icon'     => '📦',
                'order_id' => $orderId,
            ]),
            'in_transit' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order On the Way! 🛵',
                'message'  => "Your order #{$orderId} has been picked up and is on the way.",
                'type'     => 'success',
                'icon'     => '🛵',
                'order_id' => $orderId,
            ]),
            'delivered' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Delivered! 🎉',
                'message'  => "Your order #{$orderId} has been delivered successfully.",
                'type'     => 'success',
                'icon'     => '🎉',
                'order_id' => $orderId,
            ]),
            'cancelled' => Notification::create([
                'user_id'  => $order->customer_id,
                'title'    => 'Order Cancelled ❌',
                'message'  => "Your order #{$orderId} has been cancelled.",
                'type'     => 'warning',
                'icon'     => '❌',
                'order_id' => $orderId,
            ]),
            default => null,
        };

        return response()->json([
            'message' => 'Order status updated!',
            'order'   => $order,
        ]);
    }
}