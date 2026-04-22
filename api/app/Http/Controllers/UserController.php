<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
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
            'gcash_number',
        ]));
        $user->refresh();
        return response()->json(['message' => 'Profile updated!', 'user' => $user]);
    }

    // Seller uploads their ID document
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'id_document' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $user = $request->user();
        $path = $request->file('id_document')->store('id_documents', 'public');

        $user->update([
            'id_document'         => $path,
            'verification_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Document uploaded! Awaiting admin review.',
            'user'    => $user,
        ]);
    }

    // Admin approves or rejects a seller's verification
    public function verify(Request $request, $id)
    {
        $request->validate([
            'verification_status' => 'required|in:verified,rejected',
        ]);

        $seller = User::findOrFail($id);
        $seller->update(['verification_status' => $request->verification_status]);

        $isVerified = $request->verification_status === 'verified';

        Notification::create([
            'user_id' => $seller->id,
            'title'   => $isVerified ? 'Account Verified! ✅' : 'Verification Rejected ❌',
            'message' => $isVerified
                ? 'Your seller account has been verified by the admin. You can now sell on OBRA!'
                : 'Your verification was rejected. Please re-upload a valid ID or barangay document.',
            'type'    => $isVerified ? 'success' : 'warning',
            'icon'    => $isVerified ? '✅' : '❌',
        ]);

        return response()->json([
            'message' => 'Seller verification updated!',
            'user'    => $seller,
        ]);
    }
}