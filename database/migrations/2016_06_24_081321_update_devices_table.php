<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function(Blueprint $table){
            $table->integer('phone_number')->after('fk_user_id');
            $table->integer('country_code')->after('fk_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function(Blueprint $table){
            $table->dropColumn('phone_number');
            $table->dropColumn('country_code');
        });
    }
}
