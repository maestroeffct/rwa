<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_featured_categories', function (Blueprint $table) {
            $table->increments("id");
            $table->integer('category_id')->unsigned();
            $table->string("thumbnail");

            $table->foreign('category_id')->references('id')->on('blog_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_featured_categories');
    }
};
