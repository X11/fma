<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesImdbidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('imdbid')->nullable()->after('tvdbid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('imdbid');
        });
    }
}
