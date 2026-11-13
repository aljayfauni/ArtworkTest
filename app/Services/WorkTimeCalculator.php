<?php

namespace App\Services;

use App\Models\BomCreation;
use App\Models\Holiday;
use App\Models\PriceIndication;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class WorkTimeCalculator
{
    // /**
    //  * Calculate Total Client Approval Time (From Costed to Client Approval)
    //  * Excludes weekends, holidays, and non-working hours.
    //  */
    // public static function calculateLeadTimeHours(?PriceIndication $pi = null, ?BomCreation $bom_creation = null): float
    // {
    //     // 1. Determine which model we are working with
    //     $model = $pi ?? $bom_creation;

    //     // Added a safety check for $model itself just in case both are null
    //     if (!$model || !$model->submitted_at) {
    //         return 0.0;
    //     }

    //     // ✅ FIX 1: Use $model->costingSessions() instead of $pi
    //     $finalSession = $model->costingSessions()
    //         ->where('action', '!=', 'returned')
    //         ->latest('end_date_time')
    //         ->first();
            
    //     if (!$finalSession || !$finalSession->end_date_time) {
    //         return 0.0; // Hasn't been completed yet
    //     }

    //     $schedule = WorkSchedule::where('is_active', 1)->first();
    //     if (!$schedule) {
    //         return 0.0;
    //     }

    //     // Optional: Fetch holidays here if your WorkTimeCalculator still requires them!
    //     // $holidays = Holiday::pluck('date')->toArray();

    //     // ✅ Call the single unified function
    //     return WorkTimeCalculator::calculateBusinessHours(
    //         Carbon::parse($model->submitted_at), // ✅ FIX 2: Use $model->submitted_at
    //         Carbon::parse($finalSession->end_date_time),
    //         $schedule
    //         // $holidays 
    //     );
    // }

    /**
     * Calculate Total Lead Time (From Submission to Final Completion)
     * Excludes weekends, holidays, and non-working hours.
     */
    public static function calculateLeadTimeHours(?PriceIndication $pi = null, ?BomCreation $bom_creation = null): float
    {
        // 1. Determine which model we are working with
        $model = $pi ?? $bom_creation;

        // Added a safety check for $model itself just in case both are null
        if (!$model || !$model->submitted_at) {
            return 0.0;
        }

        // ✅ FIX 1: Use $model->costingSessions() instead of $pi
        $finalSession = $model->costingSessions()
            ->where('action', '!=', 'returned')
            ->latest('end_date_time')
            ->first();
            
        if (!$finalSession || !$finalSession->end_date_time) {
            return 0.0; // Hasn't been completed yet
        }

        $schedule = WorkSchedule::where('is_active', 1)->first();
        if (!$schedule) {
            return 0.0;
        }

        // Optional: Fetch holidays here if your WorkTimeCalculator still requires them!
        // $holidays = Holiday::pluck('date')->toArray();

        // ✅ Call the single unified function
        return WorkTimeCalculator::calculateBusinessHours(
            Carbon::parse($model->submitted_at), // ✅ FIX 2: Use $model->submitted_at
            Carbon::parse($finalSession->end_date_time),
            $schedule
            // $holidays 
        );
    }

    /**
     * The single source of truth for calculating working hours.
     */
    public static function calculateBusinessHours(Carbon $start, Carbon $end, WorkSchedule $schedule): float
    {
        $holidays = Holiday::pluck('date')->toArray();
        $totalMinutes = 0;
        $currentDay = $start->copy()->startOfDay();
        $endDay = $end->copy()->startOfDay();

        while ($currentDay->lte($endDay)) {
            // Skip Weekends and Holidays
            if ($currentDay->isWeekend() || in_array($currentDay->format('Y-m-d'), $holidays)) {
                $currentDay->addDay();
                continue;
            }

            // Set the boundaries for the schedule on this specific day
            $workStart = $currentDay->copy()->setTimeFromTimeString($schedule->work_start);
            $workEnd = $currentDay->copy()->setTimeFromTimeString($schedule->work_end);
            $breakStart = $currentDay->copy()->setTimeFromTimeString($schedule->break_start);
            $breakEnd = $currentDay->copy()->setTimeFromTimeString($schedule->break_end);

            // Set the boundaries for the actual session on this specific day
            $dayStart = $currentDay->isSameDay($start) ? $start : $workStart;
            $dayEnd = $currentDay->isSameDay($end) ? $end : $workEnd;

            // Calculate overlaps
            $workOverlap = self::getOverlapMinutes($dayStart, $dayEnd, $workStart, $workEnd);
            $breakOverlap = self::getOverlapMinutes($dayStart, $dayEnd, $breakStart, $breakEnd);

            // Add the valid work time minus the break time to the total
            $totalMinutes += max(0, $workOverlap - $breakOverlap);

            $currentDay->addDay(); // Move to the next day
        }

        return round($totalMinutes / 60, 2);
    }

    /**
     * Helper to find the overlapping minutes between two time periods.
     */
    private static function getOverlapMinutes(Carbon $start1, Carbon $end1, Carbon $start2, Carbon $end2): int
    {
        $overlapStart = $start1->max($start2);
        $overlapEnd = $end1->min($end2);

        return $overlapStart->lt($overlapEnd) ? $overlapStart->diffInMinutes($overlapEnd) : 0;
    }
}