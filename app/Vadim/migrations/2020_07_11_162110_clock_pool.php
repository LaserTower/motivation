<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClockPool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_clock_pool', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->timestamp('clock_at',0);
            $table->integer('alarm_clock_schedule_id');
            $table->integer('timer_part_id');
            $table->integer('users_of_providers_id');
            $table->integer('player_id')->nullable();
            $table->boolean('in_progress')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alarm_clock_pool');
    }
}
