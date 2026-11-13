<?php

namespace App\Services;

use App\Models\CostingSession;
use App\Models\WorkSchedule;
use App\Models\Holiday; // ✅ Make sure to import this
use Carbon\Carbon;

class CostingSessionLog
{
    public static function start(
        int $assigneeUserId, 
        ?int $priceIndicationId = null, 
        ?int $bomCreationId = null
    ): CostingSession {
        $workScheduleId = WorkSchedule::where('is_active', 1)->first()?->id; 
        
        return CostingSession::create([
            'work_schedule_id'    => $workScheduleId,
            'assignee_user_id'    => $assigneeUserId,
            'price_indication_id' => $priceIndicationId,
            'bom_creation_id'     => $bomCreationId,
            'start_date_time'     => Carbon::now(),
        ]);
    }

    /**
     * Find the active session for a specific document and complete it.
     */
    public static function end(
        ?int $priceIndicationId = null, 
        ?int $bomCreationId = null,
        ?int $assigneeUserId = null,
        ?string $action = null
    ): ?CostingSession {
        
        // Build the query to find an open session
        $query = CostingSession::with('workSchedule')->whereNull('end_date_time');

        if ($priceIndicationId) {
            $query->where('price_indication_id', $priceIndicationId);
        }

        if ($bomCreationId) {
            $query->where('bom_creation_id', $bomCreationId);
        }

        if ($assigneeUserId) {
            $query->where('assignee_user_id', $assigneeUserId);
        }

        // Grab the oldest active session that matches
        $session = $query->oldest('start_date_time')->first();

        if ($session) {
            $now = Carbon::now();
            
            // 1. Temporarily set the end date so our calculation logic can use it
            $session->end_date_time = $now;
            $session->action = $action;

            // 2. Calculate the hours (Returns 0 if action is 'returned')
            $totalHours = self::calculateHours($session);

            // 3. Save everything to the database at once, including our pre-calculated column
            // Make sure 'computed_total_hrs' is added to your costing_sessions migration & fillable array!
            $session->update([
                'end_date_time'      => $now,
                'action'             => $action,
                'computed_total_hrs' => $totalHours, 
            ]);
        }

        return $session;
    }

    /**
     * ========================================================================
     * INTERNAL CALCULATION ENGINE
     * ========================================================================
     */

    /**
     * Calculate total working hours excluding breaks, weekends, and holidays.
     */
    public static function calculateHours(CostingSession $session): float
    {
        // Ignore if action is 'returned' or if it lacks a schedule
        if ($session->action === 'returned' || !$session->workSchedule || !$session->workSchedule->is_active) {
            return 0.0;
        }

        // ✅ Call the single unified function
        return WorkTimeCalculator::calculateBusinessHours(
            Carbon::parse($session->start_date_time),
            Carbon::parse($session->end_date_time),
            $session->workSchedule
        );
    }
}