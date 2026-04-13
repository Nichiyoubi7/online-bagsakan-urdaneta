<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // -----------------------------------------------
    // VIEW — buyer sees their cart
    // -----------------------------------------------
    public function index(Request $request)
    {
        $user = $request->user();

        // Get or create a cart for this user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Load the items with product info
        $cart->load('items.product');

        return response()->json($cart);
    }

    // -----------------------------------------------
    // ADD — buyer adds a product to cart
    // -----------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $product = Product::find($request->product_id);

        // Check if product has enough stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Not enough stock available.',
            ], 400);
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if this product is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Product already in cart — just update the quantity
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // Product not in cart yet — add it
            $cartItem = CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        $cart->load('items.product');

        return response()->json([
            'message' => 'Product added to cart!',
            'cart'    => $cart,
        ]);
    }

    // -----------------------------------------------
    // UPDATE — buyer changes quantity of a cart item
    // -----------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();

        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message'   => 'Cart updated!',
            'cart_item' => $cartItem,
        ]);
    }

    // -----------------------------------------------
    // REMOVE — buyer removes one item from cart
    // -----------------------------------------------
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();

        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart.',
        ]);
    }
}