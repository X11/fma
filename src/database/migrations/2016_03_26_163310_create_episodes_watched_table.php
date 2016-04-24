<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesWatchedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes_watched', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->foreign('episode_id')->references('id')->on('episodes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('episodes_watched');
    }
}
