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
        Schema::create('time_lines', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('stop_time')->nullable();
            $table->string('user_id')->nullable();
            $table->string('status')->nullable(); // adjusts default as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('your_table_timename');
    }
};
