<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->integer('color_id')->unsigned()->nullable()->after('preview_img');

            $table->foreign('color_id')->references('id')->on('theme_colors_fonts')->cascadeOnDelete();
        });
    }


};
