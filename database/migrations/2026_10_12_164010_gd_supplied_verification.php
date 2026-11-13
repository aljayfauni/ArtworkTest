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
         Schema::create('file_verifications', function (Blueprint $table) {
            $table->id();
            
            // Required string fields
            $table->string('client');
            $table->string('item_description');
            
            // Flattened array fields stored as strings
            $table->string('file_source')->nullable();
            $table->string('application')->nullable();
            $table->string('color')->nullable();
            $table->string('text_specs')->nullable();
            $table->string('other_specs')->nullable();
            $table->string('image_type')->nullable();
            
            // Optional string fields
            $table->string('file_source_other')->nullable();
            $table->string('size_spread')->nullable();
            $table->string('size_folded')->nullable();
            $table->string('number_of_colors')->nullable();
            $table->string('verified_by_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('file_verifications');
    }
};
