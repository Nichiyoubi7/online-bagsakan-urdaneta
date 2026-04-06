<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->search, fn($q) => $q->where('name', 'ilike', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('category_id', 'asc')->orderBy('name', 'asc')
            ->paginate(12);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'weight_kg'   => 'nullable|numeric|min:0',
        ]);

        $product = Product::create([
            'user_id'        => $request->user()->id,
            'category_id'    => $request->category_id,
            'name'           => $request->name,
            'sku'            => 'SKU-' . strtoupper(uniqid()),
            'description'    => $request->description,
            'price'          => $request->price,
            'original_price' => $request->original_price,
            'stock'          => $request->stock,
            'weight_kg'      => $request->weight_kg ?? 0,
            'status'         => 'active',
        ]);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'status'      => 'sometimes|in:active,inactive',
        ]);

        $product->update($request->only([
            'name', 'description', 'price', 'original_price',
            'stock', 'status', 'category_id', 'weight_kg'
        ]));

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully!']);
    }
}
