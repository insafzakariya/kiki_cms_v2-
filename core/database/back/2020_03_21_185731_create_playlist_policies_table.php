<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaylistPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_policies', function (Blueprint $table){

            $table->integer('playlist_id');
            $table->integer('policy_id');
            $table->integer('pollicy_type')->comment("1 = cotnet policy , 2 = advertisment policy" );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_genres');
    }
}
