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
        Schema::create('item_lists', function (Blueprint $table) {
            $table->id(); // int, auto-incrementing primary key
            $table->string('item_code', 50)->nullable(); // nvarchar(50) Checked
            $table->string('item_name', 100)->nullable(); // nvarchar(100) Checked
            $table->timestamps(); // Automatically creates nullable created_at and updated_at datetime fields
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
           Schema::dropIfExists('item_lists');
    }
};
