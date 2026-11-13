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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data'); // Keep this for display text/json
            
            // Add your structural query columns here:
            $table->string('recieved_by')->nullable();
            $table->string('item_code')->nullable();
            $table->unsignedBigInteger('artwork_id')->nullable(); 

            $table->timestamp('read_at')->nullable();
            $table->text('created_by')->nullable();
            $table->timestamps();

            // Add indexes for fast MSSQL querying
            $table->index('item_code');
            $table->index('artwork_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
