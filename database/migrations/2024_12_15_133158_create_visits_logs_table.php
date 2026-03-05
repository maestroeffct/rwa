<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->nullable();
            $table->integer('targetable_id')->unsigned();
            $table->string('targetable_type');
            $table->integer('visitor_id')->unsigned()->nullable();
            $table->string('visitor_uid')->nullable();
            $table->bigInteger('visited_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits_logs');
    }
};
