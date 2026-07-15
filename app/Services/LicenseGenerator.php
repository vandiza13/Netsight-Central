<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Support\Str;

class LicenseGenerator
{
    /**
     * Generate a unique license key in the format: NS-2026-XXXX-XXXX
     * where XXXX-XXXX are random uppercase alphanumeric characters.
     */
    public static function generate(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $key = "NS-2026-{$part1}-{$part2}";
        } while (License::where('license_key', $key)->exists());

        return $key;
    }
}
