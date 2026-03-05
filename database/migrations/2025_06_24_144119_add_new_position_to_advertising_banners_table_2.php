<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertising_banners_table_2', function (Blueprint $table) {
            DB::statement("ALTER TABLE `advertising_banners` MODIFY COLUMN `position` enum('home1', 'home2', 'course', 'course_sidebar', 'product_show', 'bundle', 'bundle_sidebar', 'upcoming_course', 'upcoming_course_sidebar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `id`");
        });
    }

};
