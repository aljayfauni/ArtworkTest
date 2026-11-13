<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('file_specifications', function (Blueprint $table) {
            $table->id();
            
            // Required field
            $table->string('item_code');
            
            // Text / Textarea field
            $table->text('instruction_agreement')->nullable();
            
            // Array fields stored as strings (to be flattened)
            $table->string('printing_process')->nullable();
            $table->string('special_process')->nullable();
            $table->string('efile_format')->nullable();
            $table->string('color_guide')->nullable();
            
            // Numeric / Integer fields
            $table->integer('output_digital_proof_pc')->nullable();
            $table->integer('output_mockup_pc')->nullable();
            
            // Boolean fields (defaulting to false ensures a clean state)
            $table->boolean('output_digital_proof_check')->default(false);
            $table->boolean('output_mockup_check')->default(false);
            
            // Date fields
            $table->date('date_requested')->nullable();
            $table->date('date_required')->nullable();
            
            // Regular string fields
            $table->string('spec_size_spread')->nullable();
            $table->string('spec_size_folded')->nullable();
            $table->string('substrate')->nullable();
            $table->string('spec_number_of_colors')->nullable();
            $table->string('color_guide_others')->nullable();
            $table->string('reference_item_code')->nullable();
            $table->string('requested_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('file_specifications');
    }
};
