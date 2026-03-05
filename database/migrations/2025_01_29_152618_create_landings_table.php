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
        Schema::create('landings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->unique();
            $table->string('preview_img')->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();
        });

        Schema::create('landing_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('landing_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('title');

            $table->foreign('landing_id')->on('landings')->references('id')->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landings');
        Schema::dropIfExists('landing_translations');
    }
};
