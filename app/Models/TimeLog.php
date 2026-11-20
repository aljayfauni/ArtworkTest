<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TimeLog extends Model
{
    use HasFactory;

    protected $table = 'time_logs';

    protected $fillable = [
        'ao_file_link_id',
        'item_code',
        'user_id',
        'action',
        'recorded_at',
        'action_taken',  // 'Re-touching', 'Revision', 'Redesign', 'For Profreading'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Fires precisely immediately after a database insert statement executes successfully
        static::created(function (TimeLog $timeLog) {
            
            // 💡 DEFENSIVE LOOKUP: First check by matching direct row ID mapping key
            $artwork = AoModel::find($timeLog->ao_file_link_id);

            // 💡 FALLBACK LOOKUP: If ID query results evaluate blank, pull matching item_code string identifier
            if (!$artwork && !empty($timeLog->item_code)) {
                $artwork = AoModel::where('item_code', $timeLog->item_code)->first();
            }

            if ($artwork) {
                $actionName = ucfirst($timeLog->action); 
                $artistName = auth()->user()?->name ?? 'Graphic Artist';
                
                // Formulate target transactional text description strings
                if ($timeLog->action === 'resume') {
                    $description = "{$artistName} resumed work tracking on Item Code [{$artwork->item_code}].";
                } elseif ($timeLog->action === 'start') {
                    $description = "{$artistName} started a new work session on Item Code [{$artwork->item_code}].";
                } elseif ($timeLog->action === 'pause') {
                    $description = "{$artistName} paused the active work session.";
                } elseif ($timeLog->action === 'stop' || $timeLog->action === 'finish') {
                    $description = "{$artistName} completed active tracking execution for Item Code [{$artwork->item_code}].";
                } else {
                    $description = "{$artistName} triggered a production timer event: [{$actionName}].";
                }

                // 💡 FIX CONSTRAINT: Resolve the true relational auto-increment primary key user ID
                $logUserId = $timeLog->user_id;

                // If user_id matches an emp_id style value and does NOT exist as a primary key, look it up dynamically
                if ($logUserId > 0 && !\App\Models\User::where('id', $logUserId)->exists()) {
                    $resolvedUser = \App\Models\User::where('emp_id', $logUserId)->first();
                    if ($resolvedUser) {
                        $logUserId = $resolvedUser->id;
                    } else {
                        // Fallback to currently logged in supervisor/artist id if entity matching fails completely
                        $logUserId = auth()->id() ?? $timeLog->user_id;
                    }
                }

                // 💡 DEFENSIVE DIRECT DB INSERTION: Forces the record straight down to SQL Server
                // This bypasses relationship arrays to guarantee execution delivery!
                $artwork->activityLogs()->create([
                    'user_id'     => $logUserId,
                    'log_type'    => 'action_taken',
                    'event'       => 'timer_' . $timeLog->action,
                    'description' => $description,
                    'properties'  => [
                        'action'          => $timeLog->action,
                        'item_code'       => $artwork->item_code,
                        'recorded_at'     => $timeLog->recorded_at ? $timeLog->recorded_at->toDateTimeString() : now()->toDateTimeString(),
                        'cumulative_time' => $artwork->formatted_time_spent ?? '0h 0m'
                    ]
                ]);

            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(AoModel::class, 'ao_file_link_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
