<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // View all addresses
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();
        return response()->json($addresses);
    }

    // Add a new address
    public function store(Request $request)
    {
        $request->validate([
            'label'    => 'required|string|max:100',
            'street'   => 'required|string',
            'barangay' => 'required|string',
            'city'     => 'required|string',
            'province' => 'required|string',
            'zip_code' => 'nullable|string|max:10',
        ]);

        $address = Address::create([
            'user_id'  => $request->user()->id,
            'label'    => $request->label,
            'street'   => $request->street,
            'barangay' => $request->barangay,
            'city'     => $request->city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
        ]);

        return response()->json([
            'message' => 'Address added!',
            'address' => $address,
        ], 201);
    }

    // Delete an address
    public function destroy(Request $request, $id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$address) {
            return response()->json([
                'message' => 'Address not found.',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'message' => 'Address deleted.',
        ]);
    }
}