<?php

use App\Models\QrCodeModel;
use App\Services\QrCodeService;
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
