<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MessagesAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_attachments', function(Blueprint $table){
            $table->increments('id');
            $table->text('url');
            $table->integer('fk_message_id')->unsigned();
            $table->integer('fk_user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('messages_attachments', function(Blueprint $table){
            $table->foreign('fk_message_id')->references('id')->on('messages');
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::table('messages', function(Blueprint $table){
            $table->dropColumn('msg_attachment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages_attachments');

        Schema::table('messages', function(Blueprint $table){
            $table->string('msg_attachment');
        });
    }
}
