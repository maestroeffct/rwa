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
        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->nullable()->after('reserve_meeting_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

};
