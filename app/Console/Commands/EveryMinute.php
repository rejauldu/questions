<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TransformerController;

class EveryMinute extends Command
{
    /**
     * The signature remains the same for your cPanel/system settings.
     */
    protected $signature = 'train:ai';

    protected $description = 'Triggers the TransformerController trainOne method via Cron';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('AI_CRON_DEBUG: Cron triggered trainOne starting.');

        try {
            // We resolve the controller from the container and call the method directly.
            $controller = app(TransformerController::class);
            $response = $controller->trainOne();

            // Log the result from the controller's JSON response
            Log::info('AI_CRON_DEBUG: ' . json_encode($response->getData()));

        } catch (\Exception $e) {
            Log::error('AI_CRON_DEBUG: Cron training failed! ' . $e->getMessage());
        }
    }
}