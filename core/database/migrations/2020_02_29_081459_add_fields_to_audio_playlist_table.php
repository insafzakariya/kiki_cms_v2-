<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAudioPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_playlist', function (Blueprint $table){

            $table->text('description')->nullable()->after('name');
            $table->date('release_date')->nullable()->after('publish_date');
            $table->text('content_policy')->nullable();
            $table->text('advertisement_policy')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
