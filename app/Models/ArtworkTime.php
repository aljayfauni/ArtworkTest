<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkTime extends Model
{
    use HasFactory;
    protected $table = 'artwork_times';
    protected $fillable = [
        
        'artwork_id',
        'action',
        'start_date_time',
        'end_date_time',
      
 
    ];
}
