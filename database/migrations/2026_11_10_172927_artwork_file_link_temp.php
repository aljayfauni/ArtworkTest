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
        Schema::create('artwork_file_link_temp', function (Blueprint $table) {
            $table->id(); // Auto-incrementing INT (Primary Key, NOT NULL)
            
            $table->string('item_code', 255)->nullable();
            $table->string('mo', 255)->nullable();
            $table->string('ao_name', 255)->nullable();
            $table->integer('ao_user_id')->nullable();
            $table->string('file_link', 2048)->nullable();
            $table->string('from_email', 255)->nullable();
            $table->string('to_email', 255)->nullable();
            $table->text('file_info')->nullable();
            $table->text('description')->nullable();
            
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_opened')->default(false);
            
            $table->string('status', 50)->nullable();
            $table->boolean('approved')->nullable();
            $table->integer('revision')->nullable();
            $table->string('retouching', 100)->nullable();
            $table->string('spread_size', 100)->nullable();
            $table->string('folded_size', 100)->nullable();
            $table->string('colors', 255)->nullable();
            $table->integer('num_of_colors')->nullable();
            $table->string('image_type', 50)->nullable();
            $table->boolean('re_assigned')->nullable();
            $table->string('client_name', 255)->nullable();
            
            $table->string('spread_h', 50)->nullable();
            $table->string('spread_w', 50)->nullable();
            $table->string('spread_l', 50)->nullable();
            $table->string('folded_h', 50)->nullable();
            $table->string('folded_w', 50)->nullable();
            $table->string('folded_l', 50)->nullable();
            
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('assigned_to_role_id', 50)->nullable();
            $table->string('with_lsd', 255)->nullable();
            $table->string('rfa_reference', 255)->nullable();
            
            $table->timestamps(); // Creates non-nullable created_at and updated_at datetime columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
          Schema::dropIfExists('artwork_file_link_temp');
    }
};
