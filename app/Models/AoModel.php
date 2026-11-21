<?php

namespace App\Models;

use App\Notifications\ArtWorkNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AoModel extends Model
{
    use HasFactory;

    protected $table = 'ao_file_links';

    // Status constants
    const STATUS_ASSIGNED = 'Assigned To Artist';

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

                // Parentheses () ensure proper polymorphic loggable_type mapping for SQL Server
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

        // 3. RFA Reference Generation on Creation
        self::generateRfaReferenceOnCreation();
    }

    /**
     * Native MorphMany relationship to the activity_logs table.
     * Guarantees automatic populating of 'loggable_id' and 'loggable_type'.
     */
    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable')->latest();
    }

    /**
     * All historical and current artist assignments for this artwork.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(CommercialArtist::class, 'item_code', 'item_code');
    }

    /**
     * Currently active primary artist assignment (unassigned_at IS NULL).
     */
    public function activeAssignment(): HasOne
    {
        return $this->hasOne(CommercialArtist::class, 'item_code', 'item_code')
            ->whereNull('unassigned_at')
            ->latestOfMany('id');
    }

    /**
     * Kept for backward compatibility if any existing views reference $artwork->artist.
     */
    public function artist(): HasOne
    {
        return $this->activeAssignment();
    }

    /**
     * All companions assigned to this artwork.
     */
    public function companions(): HasMany
    {
        return $this->hasMany(Companion::class, 'item_code', 'item_code');
    }

    /**
     * Related file verification record.
     */
    public function fileVerification(): HasOne
    {
        return $this->hasOne(FileVerification::class, 'item_code', 'item_code');
    }

    /**
     * All time tracking logs for this artwork.
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'ao_file_link_id')->orderBy('recorded_at', 'asc');
    }

    /**
     * Initial assignment of an artwork to an artist and companions.
     */
public function assignTo(?User $artist, array $companionEmpIds, User $assignedBy): ?CommercialArtist
{
    return DB::transaction(function () use ($artist, $companionEmpIds, $assignedBy) {
        $now       = Carbon::now();
        $artistEmp = $artist?->emp_id;

        // 1. Create or update active assignment
        $assignment = $this->activeAssignment ?? $this->assignments()->make();
        $assignment->fill([
            'user_id'     => $artistEmp,
            'assigned_at' => $assignment->assigned_at ?? $now,
            'status'      => CommercialArtist::STATUS_ASSIGNED,
        ])->save();

        // 2. Sync companions (excluding the primary artist)
        $activeCompanions = $this->syncCompanions($companionEmpIds, $artistEmp);

        // 3. Update Artwork & FileVerification statuses
        $this->update(['status' => self::STATUS_ASSIGNED]);

        if ($this->fileVerification) {
            $this->fileVerification->updateQuietly(['status' => self::STATUS_ASSIGNED]);
        }

        // 4. Record activity log
        $artistName = $artist?->name ?? 'Unassigned';

        $this->activityLogs()->create([
            'user_id'     => $assignedBy->id,
            'log_type'    => 'history',
            'event'       => 'artist_assigned',
            'description' => "{$assignedBy->name} assigned artist [{$artistName}] to Item Code [{$this->item_code}].",
            'properties'  => [
                'artist_user_id' => $artistEmp,
                'artist_name'    => $artist?->name ?? 'Unknown Artist',
                'companions'     => $activeCompanions,
            ],
        ]);

        // 5. Notify artist
        if ($artist) {
            $artist->notify(new ArtWorkNotification($assignment, 'gd_to_artist'));
        }

        return $assignment;
    });
}


    /**
     * Reassign this artwork to a new artist while preserving history & auto-pausing active timers.
     */
    public function reassignTo(User $newArtist, array $companionEmpIds, User $reassignedBy, ?string $remarks = null): CommercialArtist
    {
        return DB::transaction(function () use ($newArtist, $companionEmpIds, $reassignedBy, $remarks) {
            $now          = Carbon::now();
            $previous     = $this->activeAssignment;
            $oldArtistEmp = $previous?->user_id;
            $oldMinutes   = 0;

            // 1. Close previous assignment & auto-pause their running timer
            if ($previous) {
                $startTime  = $previous->assigned_at ?? $previous->created_at;
                $oldMinutes = (int) round($startTime->diffInMinutes($now));

                $previous->update([
                    'unassigned_at'    => $now,
                    'duration_minutes' => $oldMinutes,
                    'status'           => CommercialArtist::STATUS_REASSIGNED,
                ]);

                if ($previous->user) {
                    $this->stopUserOpenTimer($previous->user->id, 'pause', $now);
                }
            }

            // 2. Create a fresh active assignment row
            $newAssignment = $this->assignments()->create([
                'user_id'     => $newArtist->emp_id,
                'assigned_at' => $now,
                'status'      => CommercialArtist::STATUS_ACTIVE,
            ]);

            // 3. Sync companions (removing the new main artist if they were a companion)
            $activeCompanions = $this->syncCompanions($companionEmpIds, $newArtist->emp_id);

            // 4. Log reassignment history
            $remarkText = !empty($remarks) ? " Remarks: {$remarks}" : '';

            $this->activityLogs()->create([
                'user_id'     => $reassignedBy->id,
                'log_type'    => 'history',
                'event'       => 'artist_reassigned',
                'description' => "{$reassignedBy->name} re-assigned item [{$this->item_code}] to [{$newArtist->name}].{$remarkText}",
                'properties'  => [
                    'old_artist_id'      => $oldArtistEmp,
                    'old_artist_minutes' => $oldMinutes,
                    'new_artist_id'      => $newArtist->emp_id,
                    'companions'         => $activeCompanions,
                    'remarks'            => $remarks,
                ],
            ]);

            // 5. Notify new artist
            $newArtist->notify(new ArtWorkNotification($newAssignment, 'gd_to_artist'));

            return $newAssignment;
        });
    }

    /**
     * Add new companions while ensuring the primary artist is never duplicated as a companion.
     */
    public function syncCompanions(array $companionEmpIds, ?string $excludeArtistEmpId = null): array
    {
        if ($excludeArtistEmpId) {
            $this->companions()->where('user_id', $excludeArtistEmpId)->delete();
        }

        foreach (array_unique($companionEmpIds) as $empId) {
            if ($empId && $empId !== $excludeArtistEmpId) {
                $this->companions()->firstOrCreate(['user_id' => $empId]);
            }
        }

        return $this->companions()->pluck('user_id')->toArray();
    }

    /**
     * Close any open 'start' or 'resume' timer for a given user on this artwork.
     */
    public function stopUserOpenTimer(int $userId, string $action = 'pause', ?Carbon $timestamp = null): void
    {
        $lastLog = $this->timeLogs()
            ->where('user_id', $userId)
            ->reorder('recorded_at', 'desc')
            ->first();

        if ($lastLog && in_array($lastLog->action, ['start', 'resume'])) {
            $this->timeLogs()->create([
                'item_code'   => $this->item_code,
                'user_id'     => $userId,
                'action'      => $action,
                'recorded_at' => $timestamp ?? Carbon::now(),
            ]);
        }
    }

    /**
     * Check if the currently logged-in user has a running timer on this artwork.
     */
    public function getIsRunningAttribute(): bool
    {
        $userId = Auth::id();
        $userLogs = $userId
            ? $this->timeLogs->where('user_id', $userId)
            : $this->timeLogs;

        $lastLog = $userLogs->last();

        return $lastLog ? in_array($lastLog->action, ['start', 'resume']) : false;
    }

    /**
     * Sum each user's individual working intervals (matches User Time Consumption table).
     */
    public function getTotalSecondsAttribute(): int
    {
        $logsByUser = $this->timeLogs->groupBy('user_id');
        $totalMilliseconds = 0;

        foreach ($logsByUser as $userLogs) {
            $tempStart = null;

            foreach ($userLogs as $log) {
                if (in_array($log->action, ['start', 'resume'])) {
                    $tempStart = Carbon::parse($log->recorded_at);
                } elseif (in_array($log->action, ['pause', 'stop', 'finish']) && $tempStart) {
                    $totalMilliseconds += $tempStart->diffInMilliseconds(Carbon::parse($log->recorded_at), true);
                    $tempStart = null;
                }
            }

            // If this user's timer is currently running, include live time up to now
            if ($tempStart) {
                $totalMilliseconds += $tempStart->diffInMilliseconds(Carbon::now(), true);
            }
        }

        return (int) floor($totalMilliseconds / 1000);
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
        $now = Carbon::now();
   
        // 1. Check Weekends (Saturday or Sunday)
        // if ($now->isWeekend()) {
        //     return true;
        // }

        // 2. Define your company holiday dates here (YYYY-MM-DD)
        $holidays = [
            '2026-11-01', // New Year's Day
            '2026-12-25', // Christmas
        ];

        if (in_array($now->toDateString(), $holidays)) {
            return true;
        }

        // 3. Check business hours window boundaries (Before 8 AM or after 6 PM)
        // if ($now->hour < 8 || $now->hour >= 18) {
        //     return true;
        // }

        return false;
    }

    /**
     * Reference number generator on creation.
     */
    private static function generateRfaReferenceOnCreation(): void
    {
        static::creating(function (AoModel $aoFileLink) {
            $dateStr = Carbon::now()->format('dmY');
            $prefix = "RFA-{$dateStr}-";

            $lastRecord = self::where('rfa_reference', 'like', "{$prefix}%")
                ->orderBy('rfa_reference', 'desc')
                ->first();

            $nextSequence = $lastRecord
                ? ((int) substr($lastRecord->rfa_reference, -3)) + 1
                : 1;

            $aoFileLink->rfa_reference = $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        });
    }
}