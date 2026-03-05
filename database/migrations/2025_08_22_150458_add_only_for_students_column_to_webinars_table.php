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
        Schema::table('webinars', function (Blueprint $table) {
            $table->boolean('only_for_students')->default(false)->after('enable_waitlist');
        });

        Schema::table('bundles', function (Blueprint $table) {
            $table->boolean('only_for_students')->default(false)->after('certificate');
        });
    }

};
