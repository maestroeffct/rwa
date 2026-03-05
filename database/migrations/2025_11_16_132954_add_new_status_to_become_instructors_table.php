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
        Schema::table('become_instructors', function (Blueprint $table) {
            DB::statement("ALTER TABLE `become_instructors` MODIFY COLUMN `status` enum('pending', 'accept', 'reject', 'pending_pay_package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' AFTER `description`");
        });
    }

};
