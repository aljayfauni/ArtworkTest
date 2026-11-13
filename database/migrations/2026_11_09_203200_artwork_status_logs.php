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
         Schema::create('artwork_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('artwork_id');
       
            $table->text('status'); // Keep this for display text/json
            
            $table->string('updated_by')->nullable();
            $table->text('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
