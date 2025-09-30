<?php

namespace App\Services;

use App\Models\QrCodeModel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\WebPWriter;
use Illuminate\Support\Facades\Storage;

class QrCodeImageGenerationService
{
    public static function generateImage(QrCodeModel $data): ResultInterface
    {
        $writer = new WebPWriter();

        $qrcode = new QrCode(
            data: $data->destination_url,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        return $writer->write($qrcode);
    }

    public static function saveImage(ResultInterface $qrCode, string $shortCode): string
    {
        $filePath = storage_path("app/public/qr-codes/$shortCode.webp");
        $qrCode->saveToFile($filePath);
        return $filePath;
    }

    public static function deleteImage(string $shortCode): bool
    {
        if (Storage::disk('public')->exists("qr-codes/$shortCode.webp")) {
            return Storage::disk('public')->delete("qr-codes/$shortCode.webp");
        }

        return false;
    }
}
