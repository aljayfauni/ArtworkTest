<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class ActionTakenLog extends Model
// {
//     use HasFactory;
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionTakenLog extends Model
{
    protected $fillable = ['user_id', 'subject_type', 'subject_id', 'action', 'remarks','rfa_reference'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
