<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use App\Services\AdminDashboardService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AdminDashboardService $dashboardService
    ) {}

    /**
     * Get dashboard statistics and recent activities.
     */
    public function dashboard(): JsonResponse
    {
        return response()->json($this->dashboardService->getDashboardData());
    }

    /**
     * Get all users.
     */
    public function getUsers(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Create a new user.
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($updatedUser)
        ]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): JsonResponse
    {
        try {
            $this->userService->deleteUser($user, auth()->id());

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Get all companies.
     */
    public function getCompanies(): JsonResponse
    {
        $companies = Company::withCount('users')->get();

        return response()->json([
            'companies' => CompanyResource::collection($companies)
        ]);
    }
}
