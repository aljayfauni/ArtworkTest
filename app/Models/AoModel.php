<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class AoModel extends Model
{
    use HasFactory;

    protected $table = 'ao_file_links';
    
    // protected $fillable = [
    //     'ao_name',
    //     'ao_user_id',
    //     'file_link',
    //     'from_email',
    //     'to_email',
    //     'status',
    //     'file_info',
    //     'description',
    //     'is_sent',
    //     'is_opened',
    //     'spread_size',
    //     'folded_size',
    //     'colors',
    //     'num_of_colors',
    //     'image_type',
    //     'client_name',
    //     'rfa_reference'
    // ];
    protected $fillable = [
    'ao_name',
    'ao_user_id',
    'file_link',
    'from_email',
    'to_email',
    'status',
    'file_info',
    'description',
    'is_sent',
    'is_opened',
    'spread_size',
    'folded_size',
    'colors',
    'num_of_colors',
    'image_type',
    'client_name',
    'rfa_reference',
    // Add these missing fields:
    'item_code',
    'application',
    'spread_h',
    'spread_w',
    'spread_l',
    'folded_h',
    'folded_w',
    'folded_l',
    'action_taken',
];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // 1. AUTOMATED HISTORY LOGS ENGINE (Runs on updates)
        static::updated(function (AoModel $model) {
            // A. Keep your legacy single-column status logger intact
            if ($model->wasChanged('status')) {
                ArtworkStatusLog::create([
                    'status'     => $model->status,
                    'artwork_id' => $model->id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            // B. History Engine: Catch other attribute changes (specs, sizes, client details, etc.)
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Skip system timestamp tracking

            // If only the status changed, we skip the metadata history log 
            // since either your action_taken logs or the legacy status logger handles it.
            if (array_key_exists('status', $changes) && count($changes) === 1) {
                return;
            }

            if (!empty($changes)) {
                $oldValues = array_intersect_key($model->getOriginal(), $changes);

                // 💡 Parentheses () ensure proper polymorphic loggable_type mapping for SQL Server
                $model->activityLogs()->create([
                    'user_id'     => Auth::id(),
                    'log_type'    => 'history',
                    'event'       => 'metadata_modified',
                    'description' => (Auth::user()?->name ?? 'System') . " updated artwork parameter settings.",
                    'properties'  => [
                        'old' => $oldValues,
                        'new' => $changes,
                    ]
                ]);
            }
        });

        // 2. INITIALIZATION TRACKER (Runs on record creation)
        static::created(function (AoModel $model) {
            if (!empty($model->status)) {
                // Legacy system tracker
                ArtworkStatusLog::create([
                    'status'     => $model->status,
                    'artwork_id' => $model->id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Modern unified action timeline logger
                $model->activityLogs()->create([
                    'user_id'     => Auth::id(),
                    'log_type'    => 'action_taken',
                    'event'       => 'status_initialized',
                    'description' => "Artwork timeline tracking initialized with status: [{$model->status}].",
                ]);
            }
        });

         // Call the private static method here
        self::generateRfaReferenceOnCreation();// rfa reference generation 
    }

    /**
     * Native MorphMany relationship to the activity_logs table.
     * Guarantees automatic populating of 'loggable_id' and 'loggable_type'.
     */
    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable')->latest();
    }

    public function artist()
    {
        return $this->hasOne(CommercialArtist::class, 'item_code', 'item_code');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'ao_file_link_id')->orderBy('recorded_at', 'asc');
    }

    public function getIsRunningAttribute(): bool
    {
        $lastLog = $this->timeLogs->last(); 
        return $lastLog ? in_array($lastLog->action, ['start', 'resume']) : false;
    }

    public function getTotalSecondsAttribute(): int
    {
        $logs = $this->timeLogs;
        $totalSeconds = 0;
        $tempStart = null;

        foreach ($logs as $log) {
            // Record the anchor whenever work starts or resumes
            if ($log->action === 'start' || $log->action === 'resume') {
                $tempStart = $log->recorded_at;
            } 
            // Whenever work is paused, stopped, or finished, add that segment block to the total sum
            elseif (in_array($log->action, ['pause', 'stop', 'finish']) && $tempStart) {
                $totalSeconds += $log->recorded_at->diffInSeconds($tempStart, true);
                $tempStart = null; // Clear the temporary anchor block
            }
        }

        // If the counter is running right now, add the active live duration up to this exact moment
        if ($this->getIsRunningAttribute() && $tempStart) {
            $totalSeconds += \Carbon\Carbon::now()->diffInSeconds($tempStart, true);
        }

        return $totalSeconds;
    }

    /**
     * Custom Accessor: Formats cumulative active timesheet durations cleanly (e.g. 1h 45m).
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        $seconds = $this->getTotalSecondsAttribute();
        $hours   = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);

        return "{$hours}h {$minutes}m";
    }

    public function getRelatedEmailsAttribute()
    {
        return self::where('file_info', $this->file_info)
            ->where('created_at', $this->created_at)
            ->where('ao_user_id', $this->ao_user_id)
            ->pluck('to_email')
            ->toArray();
    }

    /**
     * Check if today is a restricted company holiday or weekend.
     */
    public function isCompanyOffTime(): bool
    {
        $now = \Carbon\Carbon::now();

        // 1. Check Weekends (Saturday or Sunday)
        if ($now->isWeekend()) {
            return true;
        }

        // 2. Define your company holiday dates here (YYYY-MM-DD)
        $holidays = [
            '2026-11-01', // New Year's Day
            '2026-12-25', // Christmas
        ];

        if (in_array($now->toDateString(), $holidays)) {
            return true;
        }

        // 3. Check business hours window boundaries (Before 8 AM or after 6 PM)
        if ($now->hour < 8 || $now->hour >= 18) {
            return true;
        }

        return false;
    }




    //reference number
    private static function generateRfaReferenceOnCreation(): void
    {
        static::creating(function (AoModel $aoFileLink) {
            // 1. Get current date string (e.g., 10112026)
            $dateStr = Carbon::now()->format('dmY'); 
            $prefix = "RFA-{$dateStr}-";

            // 2. Grab the last reference generated today
            $lastRecord = self::where('rfa_reference', 'like', "{$prefix}%")
                ->orderBy('rfa_reference', 'desc')
                ->first();

            // 3. Determine the next increment number
            $nextSequence = $lastRecord 
                ? ((int) substr($lastRecord->rfa_reference, -3)) + 1 
                : 1;

            // 4. Set the reference right before saving (e.g., RFA-10112026-001)
            $aoFileLink->rfa_reference = $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        });
    }
}
