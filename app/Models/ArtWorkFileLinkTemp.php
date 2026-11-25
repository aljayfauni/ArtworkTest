<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ArtWorkFileLinkTemp extends Model
{
    use HasFactory;
    protected $table = 'artwork_file_link_temp';
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
    //     'client_name'

 
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
        ];
/**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // 1. Create a log when status changes on an existing record
        static::updated(function (ArtWorkFileLinkTemp $model) {
            if ($model->wasChanged('status')) {
                ArtworkStatusLog::create([
                    'status'     => $model->status,
                    'artwork_id' => $model->id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        });

        // 2. (Optional) Create an initial log when a new record is created
        static::created(function (ArtWorkFileLinkTemp $model) {
            if (!empty($model->status)) {
                ArtworkStatusLog::create([
                    'status'     => $model->status,
                    'artwork_id' => $model->id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        });
    }

    // Connect to the Commercial Artist table

// Unified stream combining both History logs and Action Taken logs
// public function activityLogs()
// {
//     return $this->hasMany(ActivityLog::class, 'loggable_id')
//                 ->where('loggable_type', self::class)
//                 ->latest();
// }

public function activityLogs()
{
    return $this->hasMany(ActivityLog::class, 'rfa_reference', 'rfa_reference')->latest();
}


public function artist()
{
    return $this->hasOne(CommercialArtist::class, 'item_code', 'item_code');
}

    public function timeLogs(): HasMany
{
    return $this->hasMany(TimeLog::class, 'ao_file_link_id')->orderBy('recorded_at', 'asc');
}

public function historyLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
{
    return $this->morphMany(HistoryLog::class, 'model')->latest();
}

public function actionTakenLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
{
    return $this->morphMany(ActionTakenLog::class, 'subject')->latest();
}

public function getIsRunningAttribute(): bool
{
    $lastLog = $this->timeLogs->last(); 
    return $lastLog ? in_array($lastLog->action, ['start', 'resume']) : false;
}

   public function getTotalSecondsAttribute(): int
    {
        // 💡 FIX: Access the relationship property collection directly.
        // This removes the duplicate query builder sorting clause that crashes SQL Server.
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
        // Add additional holiday strings as needed...
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






}
