<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Random\RandomException;

class QrCode extends Model
{
    use HasFactory;
    protected $fillable = ['shortcode', 'destination_url', 'title'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate a unique short code
     */
    public static function generateUniqueShortCode(): string
    {
        do {
            // Generate 8-character base62 string (a-z, A-Z, 0-9)
            $shortCode = Str::random(8);
        } while (self::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    /**
     * Find QR code by short code
     */
    public static function findByShortCode(string $shortCode): ?self
    {
        return self::where('short_code', $shortCode)->first();
    }

    /**
     * Get the full QR URL
     */
    public function getFullUrlAttribute(): string
    {
        return config('app.url') . '/' . $this->short_code;
    }
}
