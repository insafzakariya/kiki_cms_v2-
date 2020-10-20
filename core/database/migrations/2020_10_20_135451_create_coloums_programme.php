<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColoumsProgramme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_program', function ($table) {
            $table->string('programmeName_si')->nullable();
            $table->string('programmeName_ta')->nullable();
            $table->longText('programmeDesc_si')->nullable();
            $table->longText('programmeDesc_ta')->nullable();
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
