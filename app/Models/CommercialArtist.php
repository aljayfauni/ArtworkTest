<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommercialArtist extends Model
{
        // Status constants
    const STATUS_ACTIVE     = 'Active';
     const STATUS_ASSIGNED = 'Assigned To Artist';
    const STATUS_REASSIGNED = 'Reassigned';
    const STATUS_COMPLETED  = 'Completed';
    protected $table = 'commercial_artist';

    protected $fillable = [
        'item_code',
        'user_id',
        'assigned_at',
        'unassigned_at',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'assigned_at'   => 'datetime',
        'unassigned_at' => 'datetime',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'emp_id');
    }

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(AoModel::class, 'item_code', 'item_code');
    }
}