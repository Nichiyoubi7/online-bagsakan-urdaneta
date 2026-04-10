<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user'])
            ->where('status', 'active');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir   = $request->get('dir', 'desc');
        $allowedSorts = ['price', 'name', 'created_at', 'stock'];
        if (!in_array($sortField, $allowedSorts)) $sortField = 'created_at';

        $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int) $request->get('per_page', 12), 100);
        $products = $query->paginate($perPage);

        // Rename 'user' to 'seller' in output for clarity
        $products->getCollection()->transform(function ($product) {
            $product->seller = $product->user;
            return $product;
        });

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'user'])->findOrFail($id);
        $product->seller = $product->user;
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $request->price,
            'original_price' => $request->original_price,
            'stock'          => $request->stock,
            'weight_kg'      => $request->weight_kg ?? 0,
            'category_id'    => $request->category_id,
            'user_id'        => $request->user()->id,
            'sku'            => $request->sku ?? 'SKU-' . strtoupper(uniqid()),
            'status'         => $request->status ?? 'active',
        ]);

        $product->load(['category', 'user']);
        $product->seller = $product->user;

        return response()->json(['message' => 'Product created!', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->only([
            'name', 'description', 'price', 'original_price',
            'stock', 'weight_kg', 'category_id', 'status', 'sku',
        ]));

        $product->load(['category', 'user']);
        $product->seller = $product->user;

        return response()->json(['message' => 'Product updated!', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted!']);
    }
}