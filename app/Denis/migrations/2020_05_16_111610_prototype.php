<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Prototype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_prototypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->timestamp('published_at')->nullable(true);
            $table->boolean('published')->default(false);
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
        Schema::dropIfExists('bot_prototypes');
    }
}
