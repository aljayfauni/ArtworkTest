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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('auditable'); // Target model (nullable in case it's a system-level event)
            $table->string('event'); // e.g., 'role_changed', 'record_deleted', 'failed_login'
            $table->json('metadata')->nullable(); // Extra forensic context (e.g., old_role vs new_role)
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('rfa_reference')->nullable();
            $table->text('status')->nullable();
            $table->timestamp('created_at')->useCurrent(); // No updated_at since audit logs are immutable
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
