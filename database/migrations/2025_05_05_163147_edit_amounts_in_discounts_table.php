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
        Schema::table('discounts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `discounts`
                MODIFY COLUMN `amount` decimal(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `percent`,
                MODIFY COLUMN `max_amount` decimal(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `amount`,
                MODIFY COLUMN `minimum_order` decimal(15, 2) UNSIGNED NULL DEFAULT NULL AFTER `max_amount`");
        });
    }

};
