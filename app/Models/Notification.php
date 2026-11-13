<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    // 1. Tell Laravel that 'uuid' is the true primary identity key
    protected $primaryKey = 'uuid';

    // 2. Tell Laravel the primary key is a string UUID, not an integer number
    protected $keyType = 'string';

    // 3. Disable auto-incrementing for the primary key (the DB handles the 'id' column auto-increment)
    public $incrementing = false;

    protected $fillable = [
        'id',
        'uuid',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        // Your custom columns:
        'user_id',
        'item_code',
        'artwork_id',
        'created_by',
    ];
}
