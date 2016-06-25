<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSerieAddNetworkAirtime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('network')->nullable()->after('rating');
            $table->integer('runtime')->nullable()->after('network');
            $table->string('airtime')->nullable()->after('runtime');
            $table->string('airday')->nullable()->after('airtime');
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
            //
            $table->dropColumn(['network', 'runtime', 'airtime', 'airday']);
        });
    }
}
