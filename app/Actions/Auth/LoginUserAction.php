<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    /**
     * Handle the user login.
     *
     * @param  array<string, mixed>  $credentials
     */
    public function handle(array $credentials): ?string
    {
        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return null;
        }

        return $token;
    }
}
