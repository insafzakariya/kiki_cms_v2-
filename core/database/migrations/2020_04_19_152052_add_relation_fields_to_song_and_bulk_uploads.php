<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationFieldsToSongAndBulkUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->unsignedInteger('song_bulk_upload_id')->nullable();
        });
        Schema::table('song_bulk_uploads', function (Blueprint $table) {
            $table->integer('start')->nullable();
            $table->integer('end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table){
            $table->dropColumn('song_bulk_upload_id');
        });
        Schema::table('song_bulk_uploads', function (Blueprint $table){
            $table->dropColumn('start');
            $table->dropColumn('end');
        });
    }
}
