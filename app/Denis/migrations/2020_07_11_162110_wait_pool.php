<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WaitPool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wait_pool', function (Blueprint $table) {
            $table->timestamp('created_at', 2)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->text('provider');
            $table->integer('user_id');
            $table->text('message');
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
        Schema::dropIfExists('wait_pool');
    }
}
