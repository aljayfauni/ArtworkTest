<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FileVerification extends Model
{
    use HasFactory;
    
    protected $table = 'file_verifications';

    protected $fillable = [
        'item_code',
        'rfa_number',
        'client',
        'item_description',
        'file_source',
        'application',
        'color',
        'text_specs',
        'other_specs',
        'image_type',
        'file_source_other',
        'size_spread',
        'size_folded',
        'spread_h',
        'spread_w',
        'spread_l',
        'folded_h',
        'folded_w',
        'folded_l',
        'number_of_colors',
        'verified_by_date',
        'verified_by',
        'status', 
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // 1. TRIGGER ON RECORD CREATION (Initial submission)
        static::created(function (FileVerification $verification) {
            if (!empty($verification->item_code)) {
                
                $artwork = AoModel::where('item_code', $verification->item_code)->first();

                if ($artwork) {
                    $oldStatus = $artwork->status;
                    $newStatus = 'GD Assess';

                    // Update parent tracking record
                    $artwork->status = $newStatus;
                    $artwork->save();

                    // Push to legacy logs
                    ArtworkStatusLog::create([
                        'status'     => $newStatus,
                        'artwork_id' => $artwork->id,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // 💡 FIX: Added parentheses to activityLogs() to automatically populate loggable_type
                    $artwork->activityLogs()->create([
                        'user_id'     => Auth::id(),
                        'log_type'    => 'action_taken',
                        'event'       => 'sent_back_to_ao',
                        'description' => "File Verification submitted. Item code [{$verification->item_code}] sent back to AO. Status set to [{$newStatus}].",
                        'properties'  => [
                            'old_status'     => $oldStatus,
                            'new_status'     => $newStatus,
                            'rfa_number'     => $verification->rfa_number,
                            'verified_by'    => $verification->verified_by,
                            'system_trigger' => 'FileVerification @created'
                        ]
                    ]);
                }
            }
        });

        // 2. TRIGGER ON RECORD UPDATE (When verification details or status changes)
        static::updated(function (FileVerification $verification) {
            // Check if the verification record's status column changed
            if ($verification->wasChanged('status') && !empty($verification->item_code)) {
                
                $artwork = AoModel::where('item_code', $verification->item_code)->first();

                if ($artwork) {
                    $oldArtworkStatus = $artwork->status;
                    
                    $newArtworkStatus = 'For AO Approval'; 
                    if ($verification->status === 'Rejected') {
                        $newArtworkStatus = 'For Revision';
                    } elseif ($verification->status === 'Approved') {
                        $newArtworkStatus = 'AO Approved';
                    }

                    // Update parent ao_file_links tracking record
                    $artwork->status = $newArtworkStatus;
                    $artwork->save();

                    // Track inside legacy artwork_status_logs table
                    ArtworkStatusLog::create([
                        'status'     => $newArtworkStatus,
                        'artwork_id' => $artwork->id,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Gather modified changes from the file verification form to store in history metadata
                    $changes = $verification->getChanges();
                    unset($changes['updated_at']);
                    $oldValues = array_intersect_key($verification->getOriginal(), $changes);

                    // 💡 FIX: Added parentheses to activityLogs() to automatically populate loggable_type
                    $artwork->activityLogs()->create([
                        'user_id'     => Auth::id(),
                        'log_type'    => 'history',
                        'event'       => 'verification_updated',
                        'description' => (Auth::user()?->name ?? 'System') . " updated File Verification for [{$verification->item_code}]. Parent status set to [{$newArtworkStatus}].",
                        'properties'  => [
                            'old_artwork_status' => $oldArtworkStatus,
                            'new_artwork_status' => $newArtworkStatus,
                            'verification_old'   => $oldValues,
                            'verification_new'   => $changes,
                        ]
                    ]);
                }
            }
        });
    }
}
