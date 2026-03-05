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
        Schema::table('webinars', function (Blueprint $table) {
            DB::statement("ALTER TABLE `webinars`
                MODIFY COLUMN `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `timezone`,
                MODIFY COLUMN `image_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `thumbnail`");
        });


        Schema::table('bundles', function (Blueprint $table) {
            DB::statement("ALTER TABLE `bundles`
                MODIFY COLUMN `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `slug`,
                MODIFY COLUMN `image_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `thumbnail`");
        });


        Schema::table('upcoming_courses', function (Blueprint $table) {
            DB::statement("ALTER TABLE `upcoming_courses`
                MODIFY COLUMN `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `slug`,
                MODIFY COLUMN `image_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `thumbnail`");
        });



    }
};
