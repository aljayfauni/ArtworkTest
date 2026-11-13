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
// Schema::create('time_logs', function (Blueprint $table) {
//     $table->id();
    
//     // 🔗 Safe Numeric Link to Primary Key ID (fixes your SQL Server error)
//     $table->unsignedInteger('ao_file_link_id'); 
//     $table->foreign('ao_file_link_id')
//           ->references('ao_file_id')
//           ->on('ao_file_links')
//           ->onDelete('cascade');

//     // 💡 Simply save the item code text label column here without foreign keys
//     $table->string('item_code')->nullable(); 

//     $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
//     $table->string('action'); // 'start', 'pause', 'resume', 'stop'
//     $table->dateTime('recorded_at'); 
//     $table->timestamps();
// });

Schema::create('time_logs', function (Blueprint $table) {
    $table->id();
    
    // 🔗 Safe link to main ao_file_links table ID (This works perfectly)
    $table->unsignedInteger('ao_file_link_id'); 
    $table->foreign('ao_file_link_id')
          ->references('id')
          ->on('ao_file_links')
          ->onDelete('cascade');

    $table->string('item_code')->nullable(); 

    // 💡 FIX: Keep the user_id column to store the emp_id, 
    // but remove the ->foreign()->references() constraint that is crashing SQL Server!
    $table->unsignedInteger('user_id'); 

    $table->string('action'); // 'start', 'pause', 'resume', 'finish'
    $table->dateTime('recorded_at'); 
    $table->timestamps();
});




        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('time_logs');
    }
};
