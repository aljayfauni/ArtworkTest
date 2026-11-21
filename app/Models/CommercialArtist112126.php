<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialArtist extends Model
{
    use HasFactory;
    protected $table = 'commercial_artist';
    protected $fillable = [
        'item_code',
        'ref_number',
        'user_id'
        

 
    ];
}

