<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Admin — list all users
    public function index(Request $request)
    {
        $users = User::with("roles")
            ->when($request->role, fn($q) => $q->whereHas("roles", fn($q) => $q->where("name", $request->role)))
            ->when($request->search, fn($q) => $q->where(fn($q) => $q
                ->where("name", "ilike", "%" . $request->search . "%")
                ->orWhere("email", "ilike", "%" . $request->search . "%")
            ))
            ->orderBy("created_at", "desc")
            ->paginate(15);
        return response()->json($users);
    }

    // Admin — view single user
    public function show($id)
    {
        $user = User::with("roles")->findOrFail($id);
        return response()->json($user);
    }

    // Admin — update any user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            "name"   => "sometimes|string|max:255",
            "email"  => "sometimes|email|unique:users,email," . $id,
            "phone"  => "sometimes|nullable|string|max:20",
            "status" => "sometimes|in:active,suspended,inactive",
        ]);
        $user->update($request->only(["name", "email", "phone", "address", "status"]));
        return response()->json([
            "message" => "User updated successfully!",
            "user"    => $user->load("roles"),
        ]);
    }

    // Admin — delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(["message" => "User deleted successfully!"]);
    }

    // Any authenticated user — update own profile
    public function updateSelf(Request $request)
    {
        $user = $request->user();
        $request->validate([
            "name"  => "sometimes|string|max:255",
            "email" => "sometimes|email|unique:users,email," . $user->id,
            "phone" => "nullable|string|max:20",
        ]);
        $user->update($request->only(["name", "email", "phone"]));
        return response()->json([
            "message" => "Profile updated successfully!",
            "user"    => $user->load("roles"),
        ]);
    }
}