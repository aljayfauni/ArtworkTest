<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AoModel;
use App\Models\HistoryLog;
use App\Models\ActionTakenLog;

class CancelAbandonedRfa
{
    public function handle(Request $request, Closure $next)
    {
        // Only run on normal page visits (GET requests, non-AJAX, and logged-in users)
        if (auth()->check() && $request->isMethod('GET') && !$request->ajax()) {
            // Skip if user is still on the RFA form page or hitting reference/submit endpoints
            // (If they refresh the RFA form page, $request->routeIs('your.rfa.form.route') can still run if you remove it from this exclude list)
            $excludedRoutes = [
                'getNewReferenceNum',
                'getExistingReferenceNum',
                'getAddFormReferenceNum',
                // Add your form submit route URI here if needed
            ];

            if (!in_array($request->path(), $excludedRoutes)) {
                $userId = auth()->id();

                $activeLoggedRefs = HistoryLog::where('user_id', $userId)
                    ->whereNotNull('rfa_reference')
                    ->whereNull('status')
                    ->pluck('rfa_reference')
                    ->map(fn($ref) => trim($ref))
                    ->unique();

                if ($activeLoggedRefs->isNotEmpty()) {
                    $savedRefs = AoModel::whereIn('rfa_reference', $activeLoggedRefs)
                        ->pluck('rfa_reference')
                        ->map(fn($ref) => trim($ref))
                        ->toArray();

                    $cancelledRefs = $activeLoggedRefs->diff($savedRefs)->toArray();

                    if (!empty($cancelledRefs)) {
                        HistoryLog::whereIn('rfa_reference', $cancelledRefs)
                            ->whereNull('status')
                            ->update([
                                'status'  => 'CANCEL',
                                'remarks' => 'Cancelled due to navigating away or refreshing before submission.',
                            ]);

                        ActionTakenLog::whereIn('rfa_reference', $cancelledRefs)
                            ->whereNull('status') // Remove this line if action_taken_logs has no status column
                            ->update([
                                'status'  => 'CANCEL',
                                'remarks' => 'Cancelled due to navigating away or refreshing before submission.',
                            ]);
                    }
                }
            }
        }

        return $next($request);
    }
}
