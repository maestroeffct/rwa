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
        Schema::table('faqs', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->nullable()->after('upcoming_course_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

};
