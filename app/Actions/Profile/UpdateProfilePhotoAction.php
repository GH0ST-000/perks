<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateProfilePhotoAction
{
    /**
     * Handle the profile photo update.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): User
    {
        $user->profile_photo = $data['profile_photo'];
        $user->save();

        return $user;
    }
}
