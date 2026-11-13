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
          Schema::create('gd_supervisor_commercial', function (Blueprint $table) {
            $table->id(); // Auto-incrementing INT (Primary Key, NOT NULL)
            
            $table->string('item_code', 255)->nullable();
            $table->string('rfa_reference', 255)->nullable();
            $table->string('file_link', 2048)->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps(); // Creates non-nullable created_at and updated_at datetime columns
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('gd_supervisor_commercial');
    }
};
