<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RefreshTokenAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        // Middleware is defined in routes/api.php
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->handle($request->validated());

        // Generate token for the newly registered user
        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $token = $action->handle($request->validated());

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh(RefreshTokenAction $action): JsonResponse
    {
        $token = $action->handle();

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
