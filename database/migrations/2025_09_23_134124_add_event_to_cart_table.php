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
        Schema::table('cart', function (Blueprint $table) {
            $table->integer('event_ticket_id')->unsigned()->nullable()->after('reserve_meeting_id');
            $table->integer('quantity')->unsigned()->nullable()->after('event_ticket_id');

            $table->foreign('event_ticket_id')->references('id')->on('event_tickets')->onDelete('cascade');
        });
    }


};
