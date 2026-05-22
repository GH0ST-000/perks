<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public registration
    |--------------------------------------------------------------------------
    |
    | When false, users cannot self-register. Admins create accounts in the
    | Filament panel (corporate offer members log in via phone OTP).
    |
    */

    'registration_enabled' => (bool) env('REGISTRATION_ENABLED', false),

];
