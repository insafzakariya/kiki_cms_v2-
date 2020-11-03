<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EpisodeTableModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_episode', function ($table) {
            $table->string('episodeName_si')->nullable();
            $table->string('video_quality')->nullable();
            $table->string('episodeName_ta')->nullable();
            $table->longText('episodeDesc_si')->nullable();
            $table->longText('episodeDesc_ta')->nullable();
            $table->longText('search_tag')->nullable();
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
        //
    }
}
