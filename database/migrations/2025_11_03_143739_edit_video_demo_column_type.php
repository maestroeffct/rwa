<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `webinars`
            MODIFY COLUMN `video_demo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `image_cover`");

        DB::statement("ALTER TABLE `upcoming_courses`
            MODIFY COLUMN `video_demo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `image_cover`");

        DB::statement("ALTER TABLE `bundles`
            MODIFY COLUMN `video_demo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `image_cover`");

        DB::statement("ALTER TABLE `events`
            MODIFY COLUMN `video_demo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `video_demo_source`");
    }

};
