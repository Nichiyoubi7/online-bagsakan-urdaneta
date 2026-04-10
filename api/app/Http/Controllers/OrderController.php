<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        $query = Order::with(["items", "customer", "seller", "driver"]);

        if ($role === "customer") {
            $query->where("customer_id", $user->id);
        } elseif ($role === "seller") {
            $query->where("seller_id", $user->id);
        } elseif ($role === "driver") {
            // Driver sees: orders assigned to them OR ready orders with no driver
            $query->where(function($q) use ($user) {
                $q->where("driver_id", $user->id)
                  ->orWhere(function($q2) {
                      $q2->where("status", "ready")
                         ->whereNull("driver_id");
                  });
            });
        }
        // admin/staff see all orders

        $orders = $query->orderBy("created_at", "desc")->paginate(50);
        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(["items.product", "customer", "seller", "driver"])->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            "seller_id"        => "required|exists:users,id",
            "delivery_type"    => "required|in:delivery,pickup",
            "payment_method"   => "required|in:gcash,cod",
            "delivery_address" => "nullable|string",
            "delivery_note"    => "nullable|string",
            "items"            => "required|array|min:1",
            "items.*.product_id" => "required|exists:products,id",
            "items.*.quantity"   => "required|integer|min:1",
        ]);

        $subtotal = 0;
        $totalWeight = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item["product_id"]);
            $itemSubtotal = $product->price * $item["quantity"];
            $subtotal += $itemSubtotal;
            $totalWeight += ($product->weight_kg ?? 0) * $item["quantity"];
            $orderItems[] = [
                "product_id"   => $product->id,
                "product_name" => $product->name,
                "price"        => $product->price,
                "quantity"     => $item["quantity"],
                "subtotal"     => $itemSubtotal,
            ];
        }

        $deliveryFee = $request->delivery_type === "delivery" ? 50 : 0;
        $tip = $request->tip ?? 0;
        $total = $subtotal + $deliveryFee + $tip;

        $order = Order::create([
            "customer_id"      => $request->user()->id,
            "seller_id"        => $request->seller_id,
            "delivery_type"    => $request->delivery_type,
            "payment_method"   => $request->payment_method,
            "subtotal"         => $subtotal,
            "delivery_fee"     => $deliveryFee,
            "tip"              => $tip,
            "total"            => $total,
            "total_weight_kg"  => $totalWeight,
            "delivery_address" => $request->delivery_address,
            "delivery_note"    => $request->delivery_note,
            "status"           => "pending",
        ]);

        $order->items()->createMany($orderItems);

        return response()->json([
            "message" => "Order placed successfully!",
            "order"   => $order->load(["items", "customer", "seller"]),
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            "status" => "required|in:pending,confirmed,preparing,ready,in_transit,delivered,cancelled",
        ]);

        $user = $request->user();
        $role = $user->getRoleNames()->first();

        // When driver accepts (in_transit), assign driver_id
        if ($request->status === "in_transit" && $role === "driver") {
            $order->driver_id = $user->id;
        }

        // Mark delivered_at timestamp
        if ($request->status === "delivered") {
            $order->delivered_at = now();
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            "message" => "Order status updated!",
            "order"   => $order->load(["items", "customer", "seller", "driver"]),
        ]);
    }
}