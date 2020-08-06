<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Conversation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->integer('user_of_provider_id');
            $table->integer('prototype_id');
            $table->integer('parent_schedule_id')->nullable();
            $table->integer('next_part_id')->default(1);
            $table->jsonb('part_external_data')->default('[]');
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
        Schema::dropIfExists('bot_conversations');
    }
}
