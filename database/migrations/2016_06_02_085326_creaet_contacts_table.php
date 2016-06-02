<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaetContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function(Blueprint $table){
            $table->increments('id');
            $table->integer('fk_user_id_applicant')->nullable()->unsigned();
            $table->integer('fk_user_id_intended')->nullable()->unsigned();
            $table->enum('status', ['waiting', 'accepted', 'refused']);
            $table->timestamps();
        });

        Schema::table('contacts', function(Blueprint $table){
            $table->foreign('fk_user_id_applicant')->references('id')->on('users');
            $table->foreign('fk_user_id_intended')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('contacts');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
