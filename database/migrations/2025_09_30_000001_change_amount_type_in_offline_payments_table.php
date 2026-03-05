<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeAmountTypeInOfflinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        // Use raw SQL to avoid requiring doctrine/dbal for change()
        DB::statement('ALTER TABLE `offline_payments` MODIFY `amount` DECIMAL(15,2)');
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        // Revert back to integer
        DB::statement('ALTER TABLE `offline_payments` MODIFY `amount` INT');
    }
}


