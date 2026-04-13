<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // -----------------------------------------------
    // STORE — buyer submits a review for a product
    // -----------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string',
        ]);

        // Make sure the order belongs to this buyer
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'You can only review your own orders.',
            ], 403);
        }

        // Make sure the order is already delivered
        if ($order->status !== 'delivered') {
            return response()->json([
                'message' => 'You can only review after the order is delivered.',
            ], 400);
        }

        $review = Review::create([
            'user_id'    => $request->user()->id,
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review submitted!',
            'review'  => $review,
        ], 201);
    }

    // -----------------------------------------------
    // INDEX — anyone can see reviews for a product
    // -----------------------------------------------
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user')
            ->latest()
            ->get();

        return response()->json($reviews);
    }
}