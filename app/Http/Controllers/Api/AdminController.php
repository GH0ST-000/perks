<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics and recent activities.
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'users' => User::count(),
                'products' => 0, // Replace with actual product count when implemented
                'orders' => 0,   // Replace with actual order count when implemented
                'revenue' => 0,  // Replace with actual revenue when implemented
            ],
            'recentActivities' => [
                // This would typically come from a database query
                // For now, we'll return some mock data
                [
                    'title' => 'New user registered',
                    'status' => 'Completed',
                    'user' => 'John Doe',
                    'date' => now()->format('M d, Y')
                ],
                [
                    'title' => 'System update',
                    'status' => 'Completed',
                    'user' => 'Admin',
                    'date' => now()->subDay()->format('M d, Y')
                ]
            ]
        ]);
    }

    /**
     * Get all users.
     */
    public function getUsers(): JsonResponse
    {
        return response()->json([
            'users' => User::select('id', 'name', 'email', 'role', 'created_at')->get()
        ]);
    }

    /**
     * Create a new user.
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
