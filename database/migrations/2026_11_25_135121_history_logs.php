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
         Schema::create('history_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->morphs('model'); // Creates model_type and model_id
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('rfa_reference')->nullable();
            $table->text('remarks')->nullable();
            $table->text('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_logs');
    }
};
