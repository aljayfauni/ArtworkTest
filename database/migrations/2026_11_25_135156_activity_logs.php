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
              Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('event'); // e.g., 'login', 'export_report'
        $table->text('description')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->text('rfa_reference')->nullable();
        $table->text('status')->nullable();
        
        $table->timestamps();
        });;

        
// USE [TestApp];
// GO

// CREATE TABLE [dbo].[activity_logs] (
//     [id] BIGINT IDENTITY(1,1) NOT NULL,
//     [user_id] BIGINT NULL,
//     [event] NVARCHAR(255) NOT NULL,
//     [description] NVARCHAR(MAX) NULL,
//     [ip_address] NVARCHAR(45) NULL,
//     [user_agent] NVARCHAR(MAX) NULL,
//     [created_at] DATETIME2(0) NULL,
//     [updated_at] DATETIME2(0) NULL,
//     CONSTRAINT [PK_activity_logs] PRIMARY KEY CLUSTERED ([id] ASC),
//     CONSTRAINT [activity_logs_user_id_foreign] FOREIGN KEY ([user_id]) 
//         REFERENCES [dbo].[users] ([id]) 
//         ON DELETE SET NULL
// );
// GO
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('activity_logs');
    }
};
