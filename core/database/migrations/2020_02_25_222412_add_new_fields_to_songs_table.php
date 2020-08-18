<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNewFieldsToSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {

            $table->string('isbc_code')->nullable();
            $table->integer('composerId')->nullable();
            $table->text('project')->nullable();
            $table->text('product')->nullable();
            $table->text('featured_artists')->nullable();
            $table->text('sub_categories')->nullable();
            // $table->text('moods')->nullable();
            $table->string('line')->nullable();
            $table->string('song_publisher')->nullable();
            $table->date('release_date')->nullable();
            $table->date('uploaded_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('explicit')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {

            $table->dropColumn('isbc_code');
            $table->dropColumn('composerId');
            $table->dropColumn('line');
            $table->dropColumn('song_publisher');
            $table->dropColumn('release_date');
            $table->dropColumn('uploaded_date');
            $table->dropColumn('end_date');
            $table->dropColumn('project');
            $table->dropColumn('product');
            $table->dropColumn('featured_artists');
            $table->dropColumn('sub_categories');
            // $table->dropColumn('moods');
            $table->dropColumn('explicit');

        });
    }
}
