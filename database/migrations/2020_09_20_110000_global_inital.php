<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalInital extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_card', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('oauth')->nullable();
            $table->jsonb('variables')->nullable();
            $table->jsonb('alarm_clock_properties')->nullable();
            $table->timestamp('created_at', 2)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
        });

        Schema::create('users_of_providers', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->integer('provider_user_id');
            $table->text('provider');
            $table->integer('locked_by_conversation_id')->nullable();
            $table->integer('player_id')->nullable();
            $table->jsonb('variables')->default('[]');
            $table->timestamp('created_at', 0)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique(array('provider','provider_user_id'),'provider_user_id_unique');
            $table->foreign('player_id')->references('player_id')->on('player_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_card');
        Schema::dropIfExists('users_of_providers');
    }
}
