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
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::table('comments_reports', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });


        Schema::table('webinar_reviews', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->nullable()->after('bundle_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

    }

};
