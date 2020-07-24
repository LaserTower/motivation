<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImproveProgramPrototypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('improve_program_prototypes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('settings_bot_id');
            $table->string('name', 100);
            $table->jsonb('payload');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('improve_program_prototypes');
    }
}
