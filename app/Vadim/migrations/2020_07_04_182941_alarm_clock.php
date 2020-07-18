<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlarmClock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_clock_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->nullable();
            $table->string('timezone', 100);
            $table->integer('alarm_clock_prototype_id');
            $table->jsonb('clock_external_data');
            $table->timestamp('created_at', 0)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alarm_clock');
    }
}
