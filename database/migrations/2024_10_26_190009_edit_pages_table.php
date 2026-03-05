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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('name');
            $table->string('cover')->nullable()->after('icon');
            $table->string('header_icon')->nullable()->after('cover');
        });

        Schema::table('page_translations', function (Blueprint $table) {
            $table->text('subtitle')->nullable()->after('title');
        });

    }

};
