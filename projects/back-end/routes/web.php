<?php

use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{shortcode}', function (string $shortcode) {
    $qrCode = QrCodeService::findByShortCode($shortcode);

    if (!$qrCode) {
        abort(404, 'QR code not found');
    }

    return redirect($qrCode->destination_url, 302);
})->middleware('throttle:60,1');
