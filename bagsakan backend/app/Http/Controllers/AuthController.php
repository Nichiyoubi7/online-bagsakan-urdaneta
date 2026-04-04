<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // -----------------------------------------------
    // REGISTER — creates a new user account
    // -----------------------------------------------
    public function register(Request $request)
    {
        // Step 1: Validate the data coming in
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:buyer,seller,driver',
            'phone'    => 'nullable|string|max:20',
        ]);

        // Step 2: Create the user in the database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // encrypts the password
            'role'     => $request->role,
            'phone'    => $request->phone,
        ]);

        // Step 3: Generate a login token for this user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Step 4: Send back the user info and token
        return response()->json([
            'message' => 'Registration successful!',
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    // -----------------------------------------------
    // LOGIN — checks credentials, returns token
    // -----------------------------------------------
    public function login(Request $request)
    {
        // Step 1: Validate incoming data
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Step 2: Find the user by email
        $user = User::where('email', $request->email)->first();

        // Step 3: Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // Step 4: Generate a new token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Step 5: Send back the user info and token
        return response()->json([
            'message' => 'Login successful!',
            'token'   => $token,
            'user'    => $user,
        ], 200);
    }

    // -----------------------------------------------
    // LOGOUT — deletes the token (logs user out)
    // -----------------------------------------------
    public function logout(Request $request)
    {
        // Delete the current token being used
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }

    // -----------------------------------------------
    // ME — returns the currently logged in user
    // -----------------------------------------------
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}