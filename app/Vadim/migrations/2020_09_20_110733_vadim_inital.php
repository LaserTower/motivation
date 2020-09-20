<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VadimInital extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_scenario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('settings_scenario_id');
            $table->string('name', 100);
            $table->jsonb('payload');
            $table->timestamps(0);
            $table->foreign('settings_scenario_id')->references('id')->on('conversations_scenario');
        });
        
        Schema::create('players_program', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_of_providers_id')->nullable();
            $table->integer('program_scenario_id');
            $table->jsonb('clock_external_data');
            $table->timestamp('created_at', 0)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('program_scenario_id')->references('id')->on('program_scenario');
            $table->foreign('users_of_providers_id')->references('id')->on('users_of_providers');
        });

        Schema::create('alarm_clock_pool', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->timestamp('clock_at',0);
            $table->integer('players_program_id');
            $table->integer('timer_part_id');
            $table->integer('users_of_providers_id');
            $table->integer('player_id')->nullable();
            $table->boolean('in_progress')->default(false);
            $table->foreign('users_of_providers_id')->references('id')->on('users_of_providers');
            $table->foreign('players_program_id')->references('id')->on('players_program');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players_program');
        Schema::dropIfExists('alarm_clock_pool');
        Schema::dropIfExists('program_scenario');
    }
}
