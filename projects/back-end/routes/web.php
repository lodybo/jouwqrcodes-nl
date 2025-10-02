<?php

use App\Services\QrCodeImageGenerationService;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{shortcode}', function (QrCodeService $qrCodeService, string $shortcode) {
    $qrCode = $qrCodeService->findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    return redirect($qrCode->destination_url, 302);
})->middleware('throttle:60,1');

Route::get('/qr/{shortcode}/image', function (QrCodeService $qrCodeService, string $shortcode) {
    $qrCode = $qrCodeService->findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    $full_url = $qrCodeService->getFullUrl($qrCode);
    $qrImage = QrCodeImageGenerationService::generateImage($full_url);

    return response($qrImage->getString(), 200)
        ->header('Content-Type', $qrImage->getMimeType())
        ->header('Content-Disposition', 'inline; filename="qrcode.webp"');
});

Route::get('/qr/{shortcode}/image/download', function (QrCodeService $qrCodeService, string $shortcode) {
    $qrCode = $qrCodeService->findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    $full_url = $qrCodeService->getFullUrl($qrCode);
    $qrImage = QrCodeImageGenerationService::generateImage($full_url);

    return response($qrImage->getString(), 200)
        ->header('Content-Type', $qrImage->getMimeType())
        ->header('Content-Disposition', 'attachment; filename="qrcode.webp"');
});

Route::get('/health', function() {
    try {
//        DB::connection()->getPdo(); // Check DB
        return response()->json(['status' => 'healthy'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'unhealthy'], 503);
    }
});
