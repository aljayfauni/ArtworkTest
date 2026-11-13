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
         Schema::create('ao_file_links', function (Blueprint $table) {
            $table->id(); // int, auto-incrementing primary key
            $table->string('ao_name', 50)->nullable(); // nvarchar(50) Checked
            $table->string('ao_user_id', 100)->nullable(); // nvarchar(100) Checked
            $table->text('file_link'); // nvarchar(1000) Checked (Text type handles long URLs gracefully)
            $table->string('from_email', 100)->nullable(); // nvarchar(100) Checked
            $table->string('to_email', 100)->nullable(); // nvarchar(100) Checked
            $table->string('file_info', 500)->nullable(); // nvarchar(100) Checked
            $table->string('description', 1500)->nullable(); // nvarchar(100) Checked
            $table->string('status',50)->nullable(); // nvarchar(100) Checked
             $table->dateTime('is_sent')->useCurrent(); // nvarchar(100) Checked
               $table->dateTime('is_opened')->nullable(); // nvarchar(100) Checked
            $table->timestamps(); // Automatically creates nullable created_at and updated_at datetime fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('ao_file_links');
    }
};
