<?php

namespace App\Services;

use App\Models\ActionTakenLog;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\HistoryLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * 1. HISTORY LOG: Data snapshot + Artist editable diary remarks.
     */
    public static function history(
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $remarks = null
    ): HistoryLog {
        return HistoryLog::create([
            'user_id'       => Auth::id(),
            'rfa_reference' => $model->rfa_reference ?? null,
            'model_type'    => get_class($model),
            'model_id'      => $model->id,
            'old_values'    => $oldValues,
            'new_values'    => $newValues ?? $model->toArray(),
            'remarks'       => $remarks,
        ]);
    }

    /**
     * 2. ACTION TAKEN LOG: Completed workflow steps + TAT duration.
     */
    public static function action(
        Model $subject,
        string $action,
        ?string $remarks = null,
        ?int $durationSeconds = null
    ): ActionTakenLog {
        return ActionTakenLog::create([
            'user_id'          => Auth::id(),
            'rfa_reference'    => $subject->rfa_reference ?? null,
            'subject_type'     => get_class($subject),
            'subject_id'       => $subject->id,
            'action'           => $action,
            'remarks'          => $remarks,
            'duration_seconds' => $durationSeconds,
        ]);
    }

    /**
     * 3. ACTIVITY LOG: Clicks, page opens, form submissions.
     */
    public static function activity(
        string $event,
        ?string $description = null,
        ?string $rfaReference = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'       => Auth::id(),
            'rfa_reference' => $rfaReference,
            'event'         => $event,
            'description'   => $description,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);
    }

    /**
     * 4. AUDIT LOG: Immutable security & forensic record.
     */
    public static function audit(
        string $event,
        ?Model $auditable = null,
        array $metadata = [],
        ?string $remarks = null,
        ?string $rfaReference = null
    ): AuditLog {
        return AuditLog::create([
            'user_id'        => Auth::id(),
            'rfa_reference'  => $rfaReference ?? $auditable?->rfa_reference,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id'   => $auditable?->id,
            'event'          => $event,
            'metadata'       => $metadata,
            'remarks'        => $remarks,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}
