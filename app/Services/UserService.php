<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers(): Collection
    {
        return User::with('company')
            ->select('id', 'name', 'email', 'role', 'company_id', 'phone', 'created_at')
            ->get();
    }

    public function createUser(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ];

        // Add company_id if provided (for managers)
        if (isset($data['company_id'])) {
            $userData['company_id'] = $data['company_id'];
        }

        // Add phone if provided
        if (isset($data['phone'])) {
            $userData['phone'] = $data['phone'];
        }

        return User::create($userData);
    }

    public function updateUser(User $user, array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        // Update company_id if provided
        if (isset($data['company_id'])) {
            $userData['company_id'] = $data['company_id'];
        }

        // Update phone if provided
        if (isset($data['phone'])) {
            $userData['phone'] = $data['phone'];
        }

        $user->update($userData);

        return $user->fresh();
    }

    public function deleteUser(User $user, int $authenticatedUserId): bool
    {
        if ($user->id === $authenticatedUserId) {
            throw new \Exception('You cannot delete your own account');
        }

        return $user->delete();
    }

    public function getTotalUsersCount(): int
    {
        return User::count();
    }
}
