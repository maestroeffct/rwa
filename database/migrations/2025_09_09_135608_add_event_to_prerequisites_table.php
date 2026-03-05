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
        Schema::table('prerequisites', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `prerequisites` MODIFY COLUMN `webinar_id` int UNSIGNED NULL AFTER `id`");

            $table->integer('event_id')->unsigned()->nullable()->after('webinar_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

};
