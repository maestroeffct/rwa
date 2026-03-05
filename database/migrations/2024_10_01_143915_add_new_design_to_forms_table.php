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
        Schema::table('forms', function (Blueprint $table) {
            $table->string("header_icon")->nullable()->after('image');
            $table->string("header_overlay_image")->nullable()->after('header_icon');
        });

        Schema::table('form_translations', function (Blueprint $table) {
            $table->text("subtitle")->nullable()->after('title');
        });
    }

};
