<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction
{
    /**
     * Handle the password update.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): User
    {
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
