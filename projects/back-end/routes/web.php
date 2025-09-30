<?php

use App\Models\QrCodeModel;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/{shortcode}/image', function (QrCodeService $qrCodeService, string $shortcode) {
    $qrCode = $qrCodeService->findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    if(Storage::disk('public')->missing('qr-codes/' . $qrCode->shortcode . '.webp')) {
        abort(404, 'QR code image not found');
    }

    return Storage::disk('public')->response('qr-codes/' . $qrCode->shortcode . '.webp', null, [
        'Content-Type' => 'image/webp',
        'Content-Disposition' => 'inline; filename="' . $qrCode->shortcode . '.webp"',
    ]);
});

Route::get('/{shortcode}/image/download', function (QrCodeService $qrCodeService, string $shortcode) {
    $qrCode = $qrCodeService->findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    if(Storage::disk('public')->missing('qr-codes/' . $qrCode->shortcode . '.webp')) {
        abort(404, 'QR code image not found');
    }

    return Storage::disk('public')->download('qr-codes/' . $qrCode->shortcode . '.webp', $qrCode->shortcode . '.webp');
});
