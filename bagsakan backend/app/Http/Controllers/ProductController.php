<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // -----------------------------------------------
    // PUBLIC — anyone can browse products
    // -----------------------------------------------
    public function publicIndex(Request $request)
    {
        $query = Product::with('shop', 'category')->where('is_active', true);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function publicShow($id)
    {
        $product = Product::with('shop', 'category', 'reviews.user')->findOrFail($id);
        return response()->json($product);
    }

    // -----------------------------------------------
    // SELLER — view their own products
    // -----------------------------------------------
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'You do not have a shop yet.'], 404);
        }

        $products = Product::with('category')->where('shop_id', $shop->id)->get();
        return response()->json($products);
    }

    // -----------------------------------------------
    // SELLER — create a new product
    // -----------------------------------------------
    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'You do not have a shop yet.'], 404);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        $product = Product::create([
            'shop_id'     => $shop->id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'unit'        => $request->unit ?? 'piece',
            'image_url'   => $imageUrl,
            'is_active'   => true,
        ]);

        return response()->json([
            'message' => 'Product created!',
            'product' => $product,
        ], 201);
    }

    // -----------------------------------------------
    // SELLER — update a product
    // -----------------------------------------------
    public function update(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'unit'        => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'is_active'   => 'sometimes|boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload — delete old one first if exists
        if ($request->hasFile('image')) {
            if ($product->image_url) {
                $oldPath = str_replace(asset('storage/'), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->image_url = asset('storage/' . $path);
        }

        $product->update($request->only([
            'name', 'description', 'price', 'stock', 'unit', 'category_id', 'is_active'
        ]));

        // Save the new image_url if it was updated
        $product->save();

        return response()->json([
            'message' => 'Product updated!',
            'product' => $product,
        ]);
    }

    // -----------------------------------------------
    // SELLER — delete a product
    // -----------------------------------------------
    public function destroy(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        // Delete image from storage if exists
        if ($product->image_url) {
            $oldPath = str_replace(asset('storage/'), '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}