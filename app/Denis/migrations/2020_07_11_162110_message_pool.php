<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessagePool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_pool', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->timestamp('created_at', 2)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->integer('conversation_id');
            $table->boolean('in_progress')->default(false);
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_pool');
    }
}
