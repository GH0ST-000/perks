<?php

namespace App\Support;

class PhoneNumber
{
    public static function normalize(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '995') && strlen($digits) >= 12) {
            $digits = substr($digits, 3);
        }

        return '+995'.$digits;
    }

    public static function display(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '995') && strlen($digits) > 9) {
            $digits = substr($digits, 3);
        }

        return $digits;
    }

    public static function toGoSms(string $phone): string
    {
        return ltrim(self::normalize($phone), '+');
    }
}
