<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commercial_artist', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('unassigned_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('status')->default('Active');
        });
    }

    public function down(): void
    {
        Schema::table('commercial_artist', function (Blueprint $table) {
            $table->dropColumn(['assigned_at', 'unassigned_at', 'duration_minutes', 'status']);
        });
    }
};   