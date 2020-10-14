<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblChannelsNewColoumnAdding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_channels', function ($table) {
            $table->string('channelName_si')->nullable();
            $table->string('channelName_ta')->nullable();
            $table->longText('channelDesc_si')->nullable();
            $table->longText('channelDesc_ta')->nullable();
            $table->longText('search_tag')->nullable();
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
