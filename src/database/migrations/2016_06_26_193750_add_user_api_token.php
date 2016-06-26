<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserApiToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 71)->unique()->after('last_login');
        });

        $users = DB::table('users')->select('id')->get();

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['api_token' => str_random(70)]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_token', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
}
