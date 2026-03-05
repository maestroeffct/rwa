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
        DB::statement("ALTER TABLE `users`
            MODIFY COLUMN `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `about`,
            MODIFY COLUMN `identity_scan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `auto_renew_subscription`,
            MODIFY COLUMN `certificate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `identity_scan`");
    }

};
