<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateSongsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs_categories', function (Blueprint $table) {
            $table->string('image', 255)->after('description')->nullable();
            $table->integer('parent_cat')->after('description');
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
        Schema::table('songs_categories', function (Blueprint $table) {
            //
        });
    }
}
