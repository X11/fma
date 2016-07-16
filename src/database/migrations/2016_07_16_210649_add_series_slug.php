<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('slug')->after('name');
            $table->index('slug');
        });

        $series = DB::table('series')->get();
        foreach($series as $serie){
            DB::table('series')->where('id', $serie->id)->update([
                'slug' => str_slug($serie->name)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
