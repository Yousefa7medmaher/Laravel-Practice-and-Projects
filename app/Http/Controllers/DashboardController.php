<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Check for token from multiple sources
        $token = $request->bearerToken()
            ?? $request->cookie('token')
            ?? $request->header('Authorization')
            ?? $request->query('auth_token'); // Added query parameter check

        // Log token sources for debugging
        \Log::info('Token sources', [
            'bearer' => $request->bearerToken(),
            'cookie' => $request->cookie('token'),
            'header' => $request->header('Authorization'),
            'query' => $request->query('auth_token'),
            'final_token' => $token
        ]);

        if (!$token) {
            // If no token, check if we can authenticate via session
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please log in to access the dashboard.');
            }
        }

        // Get the authenticated user
        $user = Auth::user();

        // If we don't have a user from session, try to get from token
        if (!$user && $token) {
            // Extract token from Bearer format if needed
            if (strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7);
            }

            try {
                // Find the user by token
                $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($personalAccessToken) {
                    $user = $personalAccessToken->tokenable;

                    // Manually log in the user to create a session
                    Auth::login($user);

                    // If token was in query parameter, set it as a cookie and redirect
                    // to remove the token from the URL (for security)
                    if ($request->query('auth_token')) {
                        return redirect()->route('dashboard')
                            ->cookie('token', $token, 60*24); // 24 hours
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error authenticating with token: ' . $e->getMessage());
            }
        }

        // If still no user, redirect to login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access the dashboard.');
        }

        // Check if user is a manager
        if ($user->role !== 'manager') {
            return redirect()->route('home')->with('error', 'You do not have permission to access the dashboard.');
        }

        // Pass the token to the view for client-side API calls
        return view('dashboard', ['auth_token' => $token]);
    }

    /**
     * Get dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'courses' => Course::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'enrollments' => Enrollment::count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get all instructors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstructors()
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $instructors = User::where('role', 'instructor')->get(['id', 'name', 'email']);

        return response()->json([
            'status' => 'success',
            'data' => $instructors
        ]);
    }
}
