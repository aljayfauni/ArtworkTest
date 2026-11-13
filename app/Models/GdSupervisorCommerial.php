<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GdSupervisorCommerial extends Model
{
    use HasFactory;

    protected $table = 'gd_supervisor_commercial';
    protected $fillable = [
        'item_code',
        'rfa_reference',
        'file_link',
        'status'
        

 
    ];
}
