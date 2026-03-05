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
        Schema::table('users_registration_packages', function (Blueprint $table) {
            $table->integer('product_count')->unsigned()->nullable()->after('meeting_count');
            $table->integer('events_count')->unsigned()->nullable()->after('product_count');
            $table->integer('meeting_packages_count')->unsigned()->nullable()->after('events_count');
        });
    }

};
