<?php

namespace App\Console\Commands;

use App\Models\QrCode;
use Illuminate\Console\Command;

class QrUpdateCommand extends Command
{
    protected $signature = 'qr:update
                            {shortCode : The short code of the QR to update}
                            {--url= : New destination URL}
                            {--title= : New title}';

    protected $description = 'Update an existing QR code';

    public function handle(): int
    {
        $shortCode = $this->argument('shortCode');
        $qr = QrCode::findByShortCode($shortCode);

        if (!$qr) {
            $this->error("QR code '{$shortCode}' not found");
            return self::FAILURE;
        }

        $url = $this->option('url');
        $title = $this->option('title');

        // Check if at least one option was provided
        if (!$url && !$title) {
            $this->error('Please provide at least --url or --title option');
            return self::FAILURE;
        }

        // Validate URL if provided
        if ($url && !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL provided');
            return self::FAILURE;
        }

        // Update fields
        if ($url) {
            $qr->destination_url = $url;
        }
        if ($title) {
            $qr->title = $title;
        }

        $qr->save();

        $this->info('QR Code updated successfully!');
        $this->newLine();
        $this->line("Short Code: {$qr->short_code}");
        $this->line("Full URL: {$qr->full_url}");
        $this->line("Destination: {$qr->destination_url}");
        $this->line("Title: " . ($qr->title ?? '-'));

        return self::SUCCESS;
    }
}
