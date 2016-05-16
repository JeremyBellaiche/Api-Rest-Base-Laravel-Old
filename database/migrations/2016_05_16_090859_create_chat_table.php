<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('chats', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->integer('fk_owner_id')->unsigned()->nullable();
            $table->integer('fk_last_entry')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('chat_users', function(Blueprint $table){
            $table->increments('id');
            $table->integer('fk_chat_id')->unsigned()->nullable();
            $table->integer('fk_user_id')->unsigned()->nullable();
            $table->integer('fk_last_message_seen')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('devices', function(Blueprint $table){
            $table->increments('id');
            $table->integer('fk_user_id')->unsigned()->nullable();
            $table->string('msisdn');
            $table->string('user_agent');
            $table->timestamps();
        });

        Schema::create('presence', function(Blueprint $table){
            $table->increments('id');
            $table->integer('fk_user_id')->unsigned()->nullable();
            $table->timestamp('last_seen');
            $table->timestamps();
        });

        Schema::create('messages', function(Blueprint $table){
            $table->increments('id');
            $table->integer('fk_chat_id')->unsigned()->nullable();
            $table->integer('fk_user_id')->unsigned()->nullable();
            $table->tinyInteger('msg_type');
            $table->text('msg_text')->nullable();
            $table->string('msg_attachment')->nullable();
            $table->timestamps();
        });

        // Foreign Keys

        Schema::table('users', function(Blueprint $table){
            $table->integer('fk_device_id')->unsigned()->nullable();
            $table->foreign('fk_device_id')->references('id')->on('devices');
        });

        Schema::table('chats', function(Blueprint $table){
            $table->foreign('fk_owner_id')->references('id')->on('users');
            $table->foreign('fk_last_entry')->references('id')->on('messages');
        });

        Schema::table('chat_users', function(Blueprint $table){
            $table->foreign('fk_chat_id')->references('id')->on('chats');
            $table->foreign('fk_user_id')->references('id')->on('users');
            $table->foreign('fk_last_message_seen')->references('id')->on('messages');
        });

        Schema::table('devices', function(Blueprint $table){
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::table('presence', function(Blueprint $table){
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::table('messages', function(Blueprint $table){
            $table->foreign('fk_chat_id')->references('id')->on('chats');
            $table->foreign('fk_user_id')->references('id')->on('users');
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
        Schema::drop('chats');
        Schema::drop('chat_users');
        Schema::drop('devices');
        Schema::drop('presence');
        Schema::drop('messages');
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('fk_device_id');
            $table->dropColumn('fk_device_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
