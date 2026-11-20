<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    use HasFactory;
    protected $table = 'companion';
    protected $fillable = [

        'item_code',
        'rfa_reference',
        'user_id',
        'status',
        'added_by'
 
    ];
}
