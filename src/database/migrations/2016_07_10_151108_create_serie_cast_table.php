<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerieCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serie_cast', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serie_id')->unsigned()->index();
            $table->integer('person_id')->unsigned()->index();
            $table->integer('sort')->unsigned()->nullable();
            $table->string('role')->nullable();
            $table->string('image')->nullable();

            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('serie_cast');
    }
}
