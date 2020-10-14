<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcm_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_group');
            $table->string('section');
            $table->string('content_type');
            $table->string('content_id');
            $table->date('notification_time');
            $table->boolean('all_audiance');
            $table->string('language');
            $table->string('english_title');
            $table->string('english_description');
            $table->string('english_image');
            $table->string('sinhala_title');
            $table->string('sinhala_description');
            $table->string('sinhala_image');
            $table->string('tamil_title');
            $table->string('tamil_description');
            $table->string('tamil_image');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fcm_notification');
    }
}
