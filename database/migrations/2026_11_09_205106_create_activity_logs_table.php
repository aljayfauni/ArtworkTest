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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->unsignedBigInteger('loggable_id');
            $table->string('loggable_type'); // Maps to App\Models\AoModel
            $table->enum('log_type', ['history', 'action_taken']); 
            $table->string('event');          // e.g., 'timer_started', 'timer_paused', 'status_changed'
            $table->text('description');      // e.g., "Juan Dela Cruz paused the timer"
            $table->json('properties')->nullable(); // Holds custom artist notes or old/new value snapshots
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
