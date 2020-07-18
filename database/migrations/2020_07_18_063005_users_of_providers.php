<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersOfProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_of_providers', function (Blueprint $table) {
            $table->unsignedBigInteger('id',true);
            $table->integer('provider_user_id');
            $table->text('provider');
            $table->integer('player_id')->nullable();
            $table->jsonb('variables')->default('[]');
            $table->timestamp('created_at', 0)->default(\DB::raw('LOCALTIMESTAMP'));
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique(array('provider','provider_user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_of_providers');
    }
}
