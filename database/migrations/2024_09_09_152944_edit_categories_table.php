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
        Schema::table('categories', function (Blueprint $table) {
            $table->string("cover_image")->nullable();
            $table->string("icon2")->nullable();
            $table->string("icon2_box_color")->nullable();
            $table->string("overlay_image")->nullable();
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->text("subtitle")->nullable();
            $table->text("bottom_seo_title")->nullable();
            $table->text("bottom_seo_content")->nullable();
        });
    }
};
