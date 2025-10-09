<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class RefreshTokenAction
{
    /**
     * Handle the token refresh.
     */
    public function handle(): string
    {
        return Auth::guard('api')->refresh();
    }
}
