<?php

namespace App\Console\Commands;

use App\Models\QrCode;
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
    public function handle()
    {
        $url = $this->argument('url');
        $title = $this->argument('title');

        // TODO: Move this validation to a FormRequest or a dedicated service
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL provided.');
            return self::FAILURE;
        }

        $qr = QrCode::create([
            'shortcode' => QrCodeService::generateUniqueShortCode(),
            'destination_url' => $url,
            'title' => $title ?? 'Untitled QR Code',
        ]);

        $this->info('QR Code created successfully!');
        $this->newLine();
        $this->line("Short Code: {$qr->short_code}");
        $this->line("Full URL: {$qr->full_url}");
        $this->line("Destination: {$qr->destination_url}");
        if ($qr->title) {
            $this->line("Title: {$qr->title}");
        }

        return self::SUCCESS;
    }
}
