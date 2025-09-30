<?php

namespace App\Services;

use App\Models\QrCode;
use Illuminate\Support\Str;

class QrCodeService
{

    /**
     * Generate a unique short code
     */
    public static function generateUniqueShortCode(): string
    {
        do {
            // Generate 8-character base62 string (a-z, A-Z, 0-9)
            $shortCode = Str::random(8);
        } while (QrCode::where('shortcode', $shortCode)->exists());

        return $shortCode;
    }

    /**
     * Find QR code by short code
     */
    public static function findByShortCode(string $shortCode): ?QrCode
    {
        return QrCode::where('shortcode', $shortCode)->first();
    }
}
