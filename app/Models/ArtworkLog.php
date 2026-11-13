<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkLog extends Model
{
    use HasFactory;

    protected $table = 'artwork_logs';

    protected $fillable = [
        'user_id',
        'log',
        'file_id',
        'status',
    ];

    /**
     * Relationship: The user who performed or triggered the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: The associated file/link record from ao_file_links.
     */
    public function file()
    {
        return $this->belongsTo(AoModel::class, 'file_id');
    }
}