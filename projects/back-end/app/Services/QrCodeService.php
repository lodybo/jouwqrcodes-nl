<?php

namespace App\Services;

use App\Models\QrCodeModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class QrCodeService
{

    public function __construct(private readonly QrCodeImageGenerationService $qrGenService)
    {
        //
    }

    public function createCode(string $url, ?string $title = null): QrCodeModel
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL provided.');
        }

        return QrCodeModel::create([
            'shortcode' => self::generateUniqueShortCode(),
            'destination_url' => $url,
            'title' => $title ?? 'Untitled QR Code',
        ]);
    }

    public function updateCode(string $shortcode, ?string $url = null, ?string $title = null): QrCodeModel
    {
        $qrCode = $this->findByShortCode($shortcode);
        if (!$qrCode) {
            throw new InvalidArgumentException("QR code with shortcode '{$shortcode}' not found.");
        }

        if ($url) {
            // Validate URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException('Invalid URL provided.');
            }
            $qrCode->destination_url = $url;
        }

        if ($title) {
            $qrCode->title = $title;
        }

        $qrCode->save();

        return $qrCode;
    }

    public function deleteCode(string $shortcode): bool
    {
        $qrCode = $this->findByShortCode($shortcode);
        if (!$qrCode) {
            throw new InvalidArgumentException("QR code with shortcode '{$shortcode}' not found.");
        }

        return $qrCode->delete();
    }

    /**
     * Generate a unique short code
     */
    private function generateUniqueShortCode(): string
    {
        do {
            // Generate 8-character base62 string (a-z, A-Z, 0-9)
            $shortCode = Str::random(8);
        } while (QrCodeModel::where('shortcode', $shortCode)->exists());

        return $shortCode;
    }

    /**
     * Find QR code by short code
     */
    public function findByShortCode(string $shortCode): ?QrCodeModel
    {
        return QrCodeModel::where('shortcode', $shortCode)->first();
    }

    /**
     * Return fully qualified URL for the QR code
     */
    public function getFullUrl(QrCodeModel $qrCode): string
    {
        return secure_url("/{$qrCode->shortcode}");
    }
}
