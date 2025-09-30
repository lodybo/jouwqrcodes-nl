<?php

namespace App\Console\Commands;

use App\Models\QrCodeModel;
use App\Services\QrCodeService;
use Illuminate\Console\Command;

class QrUpdateCommand extends Command
{
    protected $signature = 'qr:update {shortCode : The short code of the QR to update} {--url= : New destination URL} {--title= : New title}';

    protected $description = 'Update an existing QR code';

    public function handle(QrCodeService $qrCodeService): int
    {
        $shortCode = $this->argument('shortCode');

        $qr = $qrCodeService->updateCode(
            $shortCode,
            $this->option('url'),
            $this->option('title')
        );

        $this->info('QR Code updated successfully!');
        $this->newLine();
        $this->line("Short Code: {$qr->shortcode}");
        $this->line("Full URL: {$qrCodeService->getFullUrl($qr)}");
        $this->line("Destination: {$qr->destination_url}");
        $this->line("Title: " . ($qr->title ?? '-'));

        return self::SUCCESS;
    }
}
