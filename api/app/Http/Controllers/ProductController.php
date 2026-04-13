<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user', 'images']);

        // If logged in as seller, only show their own products
        $authUser = $request->user();
        if ($authUser && $authUser->getRoleNames()->first() === 'seller') {
            $query->where('user_id', $authUser->id);
        } else {
            $query->where('status', 'active');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('seller_id')) {
            $query->where('user_id', $request->seller_id);
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

        $products->getCollection()->transform(function ($product) {
            $product->seller = $product->user;
            return $product;
        });

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'user', 'images'])->findOrFail($id);
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
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:2048',
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

        // Save uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'order'      => $index,
                ]);
            }
        }

        $product->load(['category', 'user', 'images']);
        $product->seller = $product->user;

        return response()->json([
            'message' => 'Product created!',
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
            'category_id' => 'sometimes|exists:categories,id',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product->update($request->only([
            'name', 'description', 'price', 'original_price',
            'stock', 'weight_kg', 'category_id', 'sku',
            'status', 'image',
        ]));

        // If new images uploaded, delete old ones and save new
        if ($request->hasFile('images')) {
            foreach ($product->images as $old) {
                Storage::disk('public')->delete($old->path);
                $old->delete();
            }
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'order'      => $index,
                ]);
            }
        }

        $product->load(['category', 'user', 'images']);
        $product->seller = $product->user;

        return response()->json([
            'message' => 'Product updated!',
            'product' => $product,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Delete all images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted!']);
    }
}