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

        Schema::table("blog", function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `blog` MODIFY COLUMN `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `slug`");
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string("cover_image")->nullable();
            $table->string("icon")->nullable();
            $table->string("icon2")->nullable();
            $table->string("icon2_box_color")->nullable();
            $table->string("overlay_image")->nullable();
        });

        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->text('subtitle')->nullable();
        });
    }
};
