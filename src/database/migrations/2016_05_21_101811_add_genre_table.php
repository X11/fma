<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('genres')->insert([
            ['name' => 'Adventure'],
            ['name' => 'Animation'],
            ['name' => 'Children'],
            ['name' => 'Comedy'],
            ['name' => 'Crime'],
            ['name' => 'Documentary'],
            ['name' => 'Drama'],
            ['name' => 'Family'],
            ['name' => 'Fantasy'],
            ['name' => 'Food'],
            ['name' => 'Game Show'],
            ['name' => 'Home and Garden'],
            ['name' => 'Horror'],
            ['name' => 'Mini-Series'],
            ['name' => 'Mystery'],
            ['name' => 'News'],
            ['name' => 'Reality'],
            ['name' => 'Romance'],
            ['name' => 'Science-Fiction'],
            ['name' => 'Soap'],
            ['name' => 'Special Interest'],
            ['name' => 'Sport'],
            ['name' => 'Suspense'],
            ['name' => 'Talk Show'],
            ['name' => 'Thriller'],
            ['name' => 'Travel'],
            ['name' => 'Western']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('genres');
    }
}
