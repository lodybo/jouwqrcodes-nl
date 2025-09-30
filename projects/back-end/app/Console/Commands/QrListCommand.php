<?php

namespace App\Console\Commands;

use App\Models\QrCodeModel;
use Illuminate\Console\Command;

class QrListCommand extends Command
{
    protected $signature = 'qr:list';

    protected $description = 'List all QR codes';

    public function handle(): int
    {
        $qrCodes = QrCodeModel::orderBy('created_at', 'desc')->get();

        if ($qrCodes->isEmpty()) {
            $this->info('No QR codes found');
            return self::SUCCESS;
        }

        $this->table(
            ['Short Code', 'Title', 'Destination URL', 'Created At'],
            $qrCodes->map(fn($qr) => [
                $qr->shortcode,
                $qr->title ?? '-',
                strlen($qr->destination_url) > 50
                    ? substr($qr->destination_url, 0, 47) . '...'
                    : $qr->destination_url,
                $qr->created_at->format('Y-m-d H:i:s'),
            ])
        );

        return self::SUCCESS;
    }
}
