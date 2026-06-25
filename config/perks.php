<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public registration
    |--------------------------------------------------------------------------
    |
    | When true, anyone can self-register with phone OTP, claim offers, and
    | redeem them via the partner scanner. When false, only admin-created
    | accounts can log in.
    |
    */

    'registration_enabled' => (bool) env('REGISTRATION_ENABLED', true),

];
