<?php

namespace App\Console\Commands;

use App\Models\FiscalYear;
use Illuminate\Console\Command;

class UpdateFYStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiscal-year:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate fiscal year if start date is today and deactivate the previous one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // 1. Find fiscal years starting today that are NOT YET activated
        $pendingFiscalYears = FiscalYear::where('start_date', $today)
            ->where('status', '!=', 1) // Only fetch if it isn't already active
            ->get();

        // 💡 OPTIMIZATION: If it's already active, exit immediately. 
        // This prevents unnecessary database writes every minute!
        if ($pendingFiscalYears->isEmpty()) {
            $this->info('Fiscal year for today is already active or none found. Skipping.');
            return Command::SUCCESS;
        }

        $newIds = $pendingFiscalYears->pluck('id')->toArray();

        // 2. Deactivate previously active fiscal years that are not in today's list
        FiscalYear::where('status', 1)
            ->whereNotIn('id', $newIds)
            ->update(['status' => 0]);

        // 3. Activate today's fiscal years
        FiscalYear::whereIn('id', $newIds)
            ->update(['status' => 1]);

        $this->info("Fiscal year(s) updated. Activated: {$pendingFiscalYears->count()} | Deactivated: previous active year(s)");
        
        return Command::SUCCESS;
    }
}
