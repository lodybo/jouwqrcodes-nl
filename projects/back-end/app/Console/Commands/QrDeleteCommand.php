<?php

namespace App\Console\Commands;

use App\Services\QrCodeService;
use Illuminate\Console\Command;

class QrDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:delete {shortCode : The short code of the QR to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a QR code by its short code';

    /**
     * Execute the console command.
     */
    public function handle(QrCodeService $qrCodeService): int
    {
        $shortCode = $this->argument('shortCode');
        $qr = $qrCodeService->findByShortCode($shortCode);

        if (!$qr) {
            $this->error("QR code '{$shortCode}' not found");
            return self::FAILURE;
        }

        // Delete the QR code record and associated image
        $qrCodeService->deleteCode($shortCode);

        $this->info("QR code '{$shortCode}' deleted successfully.");
        return self::SUCCESS;
    }
}
