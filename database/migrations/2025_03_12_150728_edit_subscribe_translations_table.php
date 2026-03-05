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
        Schema::table('subscribe_translations', function (Blueprint $table) {
            $table->renameColumn('description', 'subtitle');
        });

        Schema::table('subscribe_translations', function (Blueprint $table) {
            $table->text('description')->nullable();
        });
    }

};
