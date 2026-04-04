<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Helper — check if the logged-in user is an admin
    private function isAdmin(Request $request)
    {
        return $request->user()->role === 'admin';
    }

    // -----------------------------------------------
    // USERS — get all users in the system
    // -----------------------------------------------
    public function users(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['message' => 'Admins only.'], 403);
        }

        $users = User::latest()->get();
        return response()->json($users);
    }

    // -----------------------------------------------
    // ORDERS — get all orders in the system
    // -----------------------------------------------
    public function orders(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['message' => 'Admins only.'], 403);
        }

        $orders = Order::with('user', 'items', 'address', 'delivery')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    // -----------------------------------------------
    // SHOPS — get all shops in the system
    // -----------------------------------------------
    public function shops(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['message' => 'Admins only.'], 403);
        }

        $shops = Shop::with('user')->latest()->get();
        return response()->json($shops);
    }

    // -----------------------------------------------
    // STATS — summary counts for the dashboard
    // -----------------------------------------------
    public function stats(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['message' => 'Admins only.'], 403);
        }

        return response()->json([
            'total_users'   => User::count(),
            'total_orders'  => Order::count(),
            'total_shops'   => Shop::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ]);
    }

    // -----------------------------------------------
    // UPDATE USER — change a user's role or status
    // -----------------------------------------------
    public function updateUser(Request $request, $id)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['message' => 'Admins only.'], 403);
        }

        $request->validate([
            'role' => 'sometimes|in:buyer,seller,admin,driver',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('role'));

        return response()->json([
            'message' => 'User updated.',
            'user'    => $user,
        ]);
    }
}