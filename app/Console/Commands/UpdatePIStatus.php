<?php

namespace App\Console\Commands;

use App\Enums\ActivityAction;
use App\Enums\StatusEnum;
use App\Models\PriceIndication;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePIStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-pi-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto updates of PI status e.g. Subject For Review after 90 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $validityDays = config('app.pi_validity_days', 90);
        
        $cutoffDate = now()->subDays($validityDays)->startOfDay();

        $excludeStatus = [
            StatusEnum::RETURNED,
            StatusEnum::SUBJECT_FOR_REVIEW,
            StatusEnum::SUBMITTED,
            StatusEnum::FOR_COSTING,
            StatusEnum::EDITING,
            StatusEnum::CANCELLED
        ];

        // 1. Let the database do the heavy lifting: only fetch rows older than 90 days
        $query = PriceIndication::whereNotNull('costed_at')
            ->whereNull('client_approval_at')
            ->where('costed_at', '<=', $cutoffDate)
            ->whereNotIn('status_id', $excludeStatus);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No price indications are past 90 days.');
            return 0;
        }

        // 2. Use chunkById to safely process records in batches of 100 without crashing memory
        $query->chunkById(100, function ($pis) {
            foreach ($pis as $item) {
                $item->status_id = StatusEnum::SUBJECT_FOR_REVIEW; 
                
                $item->save();

                ActivityLogger::log(
                    ActivityAction::SUBJECT_FOR_REVIEW,
                    $item,
                    [
                        'remarks' => 'Automatically tagged as Subject for Review due to passing the 90-day validity period.',
                        'system_generated' => true
                    ]
                );
            }
        });

        $this->info("Processed and updated {$count} past due PI(s).");

        return 0;
    }
}
