<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'ILIKE', '%' . $request->search . '%');
            });
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        return response()->json($query->paginate($perPage));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only([
            'name', 'email', 'phone', 'status',
            'store_name', 'store_description', 'store_status',
        ]));
        return response()->json(['message' => 'User updated!', 'user' => $user->load('roles')]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted!']);
    }

    public function updateSelf(Request $request)
    {
        $user = $request->user();
        $user->update($request->only([
            'name', 'phone', 'address',
            'store_name', 'store_description',
        ]));
        return response()->json(['message' => 'Profile updated!', 'user' => $user]);
    }
}