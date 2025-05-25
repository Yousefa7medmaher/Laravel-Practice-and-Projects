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

        // Create a new unique token with timestamp for uniqueness
        $tokenName = 'auth_token_' . time() . '_' . uniqid();
        $token = $user->createToken($tokenName)->plainTextToken;

        // Log the token creation for debugging (remove in production)
        \Log::info('New token created for new user registration', [
            'user_id' => $user->id,
            'email' => $user->email,
            'token_name' => $tokenName,
            'token_prefix' => substr($token, 0, 10) . '...' // Only log first 10 chars for security
        ]);

        // Determine redirect URL based on user role
        $redirectUrl = '/';
        if ($user->role === 'student') {
            $redirectUrl = '/student/dashboard'; // Redirect students to their dashboard

            // Check if there's a specific redirect parameter
            $redirect = $request->query('redirect');
            if ($redirect && str_starts_with($redirect, 'student_')) {
                $page = substr($redirect, 8); // Remove 'student_' prefix
                if (!empty($page) && $page !== 'dashboard') {
                    $redirectUrl = "/student/{$page}";
                }
            }
        }

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'redirect_url' => $redirectUrl
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

        // IMPORTANT: Delete all existing tokens for this user to ensure only one active session
        // This implements proper token rotation - old tokens become invalid
        $user->tokens()->delete();

        // Create a new unique token with timestamp for uniqueness
        $tokenName = 'auth_token_' . time() . '_' . uniqid();
        $token = $user->createToken($tokenName)->plainTextToken;

        // Log the token creation for debugging (remove in production)
        \Log::info('New token created for user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'token_name' => $tokenName,
            'token_prefix' => substr($token, 0, 10) . '...' // Only log first 10 chars for security
        ]);

        // Determine redirect URL based on user role
        $redirectUrl = $this->getRedirectUrlForRole($user->role, $request);

        // Log successful login with role information
        \Log::info('User login successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'redirect_url' => $redirectUrl,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'redirect_url' => $redirectUrl
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = $request->user();

        // Log the logout for debugging
        \Log::info('User logging out', [
            'user_id' => $user->id,
            'email' => $user->email,
            'tokens_count' => $user->tokens()->count()
        ]);

        // Delete all tokens for this user to ensure complete logout
        $deletedTokens = $user->tokens()->delete();

        // Log successful token deletion
        \Log::info('Tokens deleted during logout', [
            'user_id' => $user->id,
            'deleted_tokens_count' => $deletedTokens
        ]);

        return response()->json([
            'message' => 'Logged out successfully',
            'tokens_invalidated' => $deletedTokens
        ]);
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

    /**
     * Get the appropriate redirect URL based on user role
     */
    private function getRedirectUrlForRole($role, $request = null)
    {
        switch ($role) {
            case 'manager':
                return '/manager/dashboard';

            case 'instructor':
                return '/instructor/dashboard';

            case 'student':
                // Check if there's a specific redirect parameter for students
                if ($request) {
                    $redirect = $request->query('redirect');
                    if ($redirect && str_starts_with($redirect, 'student_')) {
                        $page = substr($redirect, 8); // Remove 'student_' prefix
                        if (!empty($page) && $page !== 'dashboard') {
                            return "/student/{$page}";
                        }
                    }
                }
                return '/student/dashboard';

            default:
                return '/'; // Default fallback
        }
    }

}



