<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student', // default role
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    //Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Log the request data for debugging
        \Log::info('Update Profile Request:', [
            'all' => $request->all(),
            'files' => $request->hasFile('imgProfile') ? 'Has image file' : 'No image file',
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);

        // Validate the request data
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'imgProfile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Track changes
        $changes = false;
        $updatedFields = [];

        // Process profile image upload
        if ($request->hasFile('imgProfile')) {
            $image = $request->file('imgProfile');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/profile_images', $imageName);
            $user->imgProfilePath = 'storage/profile_images/' . $imageName;
            $changes = true;
            $updatedFields[] = 'profile image';
        }

        // Update name if provided
        if ($request->filled('name')) {
            $user->name = $request->input('name');
            $changes = true;
            $updatedFields[] = 'name';
        }

        // Update email if provided
        if ($request->filled('email')) {
            $user->email = $request->input('email');
            $changes = true;
            $updatedFields[] = 'email';
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $changes = true;
            $updatedFields[] = 'password';
        }

        // Save changes if any were made
        if ($changes) {
            $user->save();

            // Refresh the user model to get updated timestamps
            $user = $user->fresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully. Updated fields: ' . implode(', ', $updatedFields),
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'info',
                'message' => 'No changes were made to your profile',
                'user' => $user
            ]);
        }
    }


    public function profile(Request $request)
{
    return response()->json([
        'user' => $request->user()
    ]);
}

}



