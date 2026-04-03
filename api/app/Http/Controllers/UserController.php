<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{
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

    public function show($id)
    {
        $user = User::with("roles")->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            "name"  => "sometimes|string|max:255",
            "email" => "sometimes|email|unique:users,email," . $id,
            "phone" => "sometimes|string|max:20",
        ]);
        $user->update($request->only(["name", "email", "phone", "address"]));
        return response()->json([
            "message" => "User updated successfully!",
            "user"    => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(["message" => "User deleted successfully!"]);
    }
}
