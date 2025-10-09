<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateProfileAction
{
    /**
     * Handle the profile update.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): User
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $user->email = $data['email'];
        }

        $user->save();

        return $user;
    }
}
