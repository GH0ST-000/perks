<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Support\Str;

class PartnerAccountService
{
    public function syncLoginUser(Partner $partner): ?User
    {
        $phone = $this->normalizePartnerPhone($partner->phone);

        if ($phone === null) {
            return null;
        }

        if (User::query()
            ->where('phone', $phone)
            ->where(function ($query) use ($partner): void {
                $query->where('partner_id', '!=', $partner->id)
                    ->orWhereNull('partner_id');
            })
            ->where('role', '!=', 'partner')
            ->exists()) {
            return null;
        }

        $user = User::query()
            ->where('partner_id', $partner->id)
            ->first();

        if (! $user) {
            $user = User::query()
                ->where('phone', $phone)
                ->where('role', 'partner')
                ->first();
        }

        $email = $partner->email ?: "partner-{$partner->id}@perks.local";

        $attributes = [
            'name' => $partner->name,
            'email' => $email,
            'phone' => $phone,
            'role' => 'partner',
            'partner_id' => $partner->id,
            'company_id' => null,
            'email_verified_at' => now(),
        ];

        if ($user) {
            $user->update($attributes);

            return $user->fresh();
        }

        return User::create([
            ...$attributes,
            'password' => bcrypt(Str::random(32)),
            'p_coins' => 0,
        ]);
    }

    public function removeLoginUser(Partner $partner): void
    {
        User::query()
            ->where('partner_id', $partner->id)
            ->where('role', 'partner')
            ->delete();
    }

    public function normalizePartnerPhone(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (strlen($digits) < 9) {
            return null;
        }

        return PhoneNumber::normalize($digits);
    }
}
