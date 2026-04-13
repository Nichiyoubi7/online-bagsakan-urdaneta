<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    // -----------------------------------------------
    // CREATE a shop
    // -----------------------------------------------
    public function store(Request $request)
    {
        if ($request->user()->role !== 'seller') {
            return response()->json(['message' => 'Sellers only.'], 403);
        }

        if ($request->user()->shop) {
            return response()->json(['message' => 'You already have a shop.'], 400);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shops', 'public');
            $logoUrl = asset('storage/' . $path);
        }

        $shop = Shop::create([
            'user_id'     => $request->user()->id,
            'name'        => $request->name,
            'description' => $request->description,
            'logo_url'    => $logoUrl,
        ]);

        return response()->json([
            'message' => 'Shop created!',
            'shop'    => $shop,
        ], 201);
    }

    // -----------------------------------------------
    // VIEW their own shop
    // -----------------------------------------------
    public function show(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'You do not have a shop yet.'], 404);
        }

        return response()->json($shop);
    }

    // -----------------------------------------------
    // UPDATE their shop
    // -----------------------------------------------
    public function update(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'You do not have a shop yet.'], 404);
        }

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle logo upload — delete old one first if exists
        if ($request->hasFile('logo')) {
            if ($shop->logo_url) {
                $oldPath = str_replace(asset('storage/'), '', $shop->logo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('shops', 'public');
            $shop->logo_url = asset('storage/' . $path);
        }

        $shop->update($request->only(['name', 'description', 'is_active']));
        $shop->save();

        return response()->json([
            'message' => 'Shop updated!',
            'shop'    => $shop,
        ]);
    }
}