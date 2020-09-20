<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DenisInital extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations_scenario', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->timestamp('published_at')->nullable(true);
            $table->boolean('published')->default(false);
            $table->jsonb('payload');
            $table->timestamps(0);
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->integer('user_of_provider_id');
            $table->integer('scenario_id');
            $table->integer('parent_program_scenario')->nullable();
            $table->integer('next_part_id')->default(1);
            $table->jsonb('part_external_data')->default('[]');
            $table->timestamp('created_at', 0)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('scenario_id')->references('id')->on('conversations_scenario');
            $table->foreign('user_of_provider_id')->references('id')->on('users_of_providers');
        });

        Schema::create('message_pool', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->timestamp('created_at', 2)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->integer('conversation_id')->nullable();
            $table->boolean('in_progress')->default(false);
            $table->text('message');
            $table->foreign('conversation_id')->references('id')->on('conversations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('conversations_scenario');
        Schema::dropIfExists('message_pool');
    }
}
