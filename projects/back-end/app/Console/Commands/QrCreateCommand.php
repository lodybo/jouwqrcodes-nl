<?php

namespace App\Console\Commands;

use App\Models\QrCodeModel;
use App\Services\QrCodeService;
use Illuminate\Console\Command;

class QrCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:create {url} {title?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a QR code for a given URL';

    /**
     * Execute the console command.
     */
    public function handle(QrCodeService $qrCodeService): int
    {
        $url = $this->argument('url');
        $title = $this->argument('title');

        $qr = $qrCodeService->createCode($url, $title);

        $this->info('QR Code created successfully!');
        $this->newLine();
        $this->line("Short Code: {$qr->shortcode}");
        $this->line("Full URL: {$qrCodeService->getFullUrl($qr)}");
        $this->line("Destination: {$qr->destination_url}");
        if ($qr->title) {
            $this->line("Title: {$qr->title}");
        }

        return self::SUCCESS;
    }
}
