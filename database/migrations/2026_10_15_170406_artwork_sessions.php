<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_schedule_id');
            $table->unsignedBigInteger('artwork_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('art_sessions');
    }
};
