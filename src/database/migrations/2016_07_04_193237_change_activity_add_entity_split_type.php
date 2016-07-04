<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeActivityAddEntitySplitType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('action')->nullable()->after('type');
            $table->integer('entity_id')->unsigned()->nullable()->after('action');
        });

        $logs = DB::table('activities')->get();

        foreach ($logs as $log) {
            $parts = explode('.', $log->type);
            $type = $parts[0];
            $action = $parts[1];

            $data = $log->data;

            $entity_id = null;
            if (isset($data['serie_id'])){
                $entity_id = $data['serie_id'];
            } elseif (isset($data['episode_id'])){
                $entity_id = $data['serie_id'];
            }

            DB::table('activities')->where('id', $log->id)->update([
                'type' => $type,
                'action' => $action,
                'entity_id' => $entity_id
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
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['action', 'entity_id']);
        });
    }
}
