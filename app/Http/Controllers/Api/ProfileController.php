<?php

namespace App\Http\Controllers\Api;

use App\Actions\Profile\UpdatePasswordAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Actions\Profile\UpdateProfilePhotoAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfilePhotoRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Update the user's profile.
     */
    public function updateProfile(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        $user = $request->user();
        $updatedUser = $action->handle($user, $request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $updatedUser,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request, UpdatePasswordAction $action): JsonResponse
    {
        $user = $request->user();
        $action->handle($user, $request->validated());

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    /**
     * Update the user's profile photo.
     */
    public function updateProfilePhoto(UpdateProfilePhotoRequest $request, UpdateProfilePhotoAction $action): JsonResponse
    {
        $user = $request->user();
        $updatedUser = $action->handle($user, $request->validated());

        return response()->json([
            'message' => 'Profile photo updated successfully',
            'user' => $updatedUser,
        ]);
    }
}
