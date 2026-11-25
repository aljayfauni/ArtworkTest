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
        Schema::create('action_taken_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who took the action
            $table->morphs('subject'); // Creates subject_type (Model) and subject_id
            $table->string('action'); // e.g., 'approved', 'rejected', 'escalated'
            $table->text('remarks')->nullable(); // Reason or notes for the action
            $table->text('rfa_reference')->nullable(); // Reason or notes for the action
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_taken_logs');
    }
};
