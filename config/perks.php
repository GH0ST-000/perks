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

    /*
    |--------------------------------------------------------------------------
    | Membership plans (Member / Limited)
    |--------------------------------------------------------------------------
    */

    'membership_plans' => [
        'member' => [
            'name' => 'Member',
            'label' => 'Member',
            'amount' => (float) env('MEMBERSHIP_MEMBER_PRICE', 19),
            'card_type' => 'standard',
            'p_coins_multiplier' => 1.0,
            'p_coins_label' => env('MEMBERSHIP_MEMBER_P_COINS_LABEL', '10 P-Coin ვიზიტზე'),
        ],
        'limited' => [
            'name' => 'Limited',
            'label' => 'Limited',
            'amount' => (float) env('MEMBERSHIP_LIMITED_PRICE', 29),
            'card_type' => 'premium',
            'p_coins_multiplier' => 1.5,
            'p_coins_label' => env('MEMBERSHIP_LIMITED_P_COINS_LABEL', '15 P-Coin ვიზიტზე'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Perks Family — monthly addon per approved relative (GEL)
    |--------------------------------------------------------------------------
    */

    'family_member_monthly_addon' => (float) env('FAMILY_MEMBER_MONTHLY_ADDON', 10),

];
