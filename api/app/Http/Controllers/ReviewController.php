<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // GET /reviews — anyone can read reviews
    public function index(Request $request)
    {
        $query = Review::with('customer:id,name')
            ->orderBy('created_at', 'desc');

        // Filter by product if product_id is passed
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by seller if seller_id is passed
        if ($request->seller_id) {
            $query->whereHas('product', fn($q) => $q->where('user_id', $request->seller_id));
        }

        $reviews = $query->paginate(20);

        // Calculate average rating for a product
        $avg = null;
        if ($request->product_id) {
            $avg = Review::where('product_id', $request->product_id)->avg('rating');
        }

        return response()->json([
            'data'       => $reviews->items(),
            'total'      => $reviews->total(),
            'avg_rating' => $avg ? round($avg, 1) : null,
        ]);
    }

    // POST /reviews — only customers who actually bought the product can review
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        // Make sure the customer actually bought this product and it was delivered
        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $user->id)
            ->where('status', 'delivered')
            ->whereHas('items', fn($q) => $q->where('product_id', $request->product_id))
            ->firstOrFail();

        // Create or update the review
        $review = Review::updateOrCreate(
            [
                'product_id'  => $request->product_id,
                'customer_id' => $user->id,
                'order_id'    => $order->id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return response()->json([
            'message' => 'Review submitted successfully!',
            'review'  => $review->load('customer:id,name'),
        ], 201);
    }

    // DELETE /reviews/{id} — only staff or admin can remove a review
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        if (!in_array($role, ['admin', 'staff'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Review::findOrFail($id)->delete();

        return response()->json(['message' => 'Review removed successfully.']);
    }
}