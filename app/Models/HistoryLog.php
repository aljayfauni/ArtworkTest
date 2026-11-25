<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class HistoryLog extends Model
// {
//     use HasFactory;
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $fillable = ['user_id', 'model_type', 'model_id', 'old_values', 'new_values','rfa_reference','remarks'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }
}
