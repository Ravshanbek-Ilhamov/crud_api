<?php

namespace App\Console\Commands;

use App\Models\ValidationPassword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteRecentValidationCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-recent-validation-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trying to delete recent validation codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $deletedRows = ValidationPassword::where('created_at', '<', now()->subMinutes(2))->delete();

            if ($deletedRows > 0) {
                Log::info("$deletedRows validation code(s) deleted successfully.");
            } else {
                Log::info('No validation codes to delete.');
            }
        } catch (\Exception $e) {
            Log::info('Failed to delete validation codes: ' . $e->getMessage());
        }
    }
}
