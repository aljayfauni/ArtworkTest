<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtworkStatusLog extends Model
{
    use HasFactory;

    protected $table = 'artwork_status_logs';

    protected $fillable = [
        'status',
        'artwork_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Automatically inject into unified activity_logs when a status log row is written
        static::created(function (ArtworkStatusLog $statusLog) {
            // Find the parent artwork asset using the artwork_id foreign key mapping
            $artwork = AoModel::find($statusLog->artwork_id);

            if ($artwork) {
                // Ensure proper polymorphic mapping structure using query parenthesis ()
                $artwork->activityLogs()->create([
                    'user_id'     => $statusLog->created_by ?? auth()->id(),
                    'log_type'    => 'action_taken',
                    'event'       => 'status_changed',
                    'description' => "Artwork production workflow status updated to [" . $statusLog->status . "].",
                    'properties'  => [
                        'new_status'        => $statusLog->status,
                        'legacy_log_row_id' => $statusLog->id,
                        'system_trigger'    => 'ArtworkStatusLog Observer Sync'
                    ]
                ]);
            }
        });
    }

    /**
     * Relationship linking back to the primary artwork asset entry tracker.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(AoModel::class, '  ');
    }

    /**
     * Relationship linking back to the user account that triggered the update.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
