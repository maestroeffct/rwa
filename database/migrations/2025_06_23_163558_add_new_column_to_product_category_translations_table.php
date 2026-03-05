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
        Schema::table('product_category_translations', function (Blueprint $table) {
            $table->string('bottom_seo_title')->nullable();
            $table->text('bottom_seo_description')->nullable();
        });
    }

};
