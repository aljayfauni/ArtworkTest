<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSpecification extends Model
{
    use HasFactory;
    protected $table = 'file_specifications';
    
    protected $fillable = [
        'item_code',
        'spec_size_spread',
        'spec_size_folded',
        'printing_process',
        'substrate',
        'spec_number_of_colors',
        'special_process',
        'output_digital_proof_check',
        'output_digital_proof_pc',
        'output_mockup_check',
        'output_mockup_pc',
        'efile_format',
        'color_guide',
        'color_guide_others',
        'reference_item_code',
        'requested_by',
        'date_requested',
        'date_required',
        'instruction_agreement',
        'spread_h',
        'spread_w',
        'spread_l',
        'folded_h',
        'folded_w',
        'folded_l',
    ];
}
