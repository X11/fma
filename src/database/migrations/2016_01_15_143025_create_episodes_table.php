<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('overview', 255)->nullable();
            $table->string('aired')->nullable();
            $table->integer('episodeNumber');
            $table->integer('episodeSeason');
            $table->integer('episodeid');
            $table->integer('serie_id')->unsigned();
            $table->timestamps();

            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('episodes');
    }
}
