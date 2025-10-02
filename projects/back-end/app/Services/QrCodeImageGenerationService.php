<?php

namespace App\Services;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Writer\PngWriter;;
use Endroid\QrCode\Writer\SvgWriter;

class QrCodeImageGenerationService
{
    public static function generateImage(string $full_url, string $format = 'webp'): ResultInterface
    {
        $writer = match($format) {
            'png' => new PngWriter(),
            'svg' => new SvgWriter(),
            default => new WebPWriter(),
        };

        $qrcode = new QrCode(
            data: $full_url,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        return $writer->write($qrcode);
    }
}
