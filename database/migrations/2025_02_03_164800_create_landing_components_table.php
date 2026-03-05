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
        Schema::create('landing_components', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('landing_id')->unsigned();
            $table->integer('component_id')->unsigned();
            $table->string('preview')->nullable();
            $table->boolean('enable')->default(false);
            $table->integer('order')->unsigned()->nullable();

            $table->foreign('landing_id')->references('id')->on('landings')->cascadeOnDelete();
            $table->foreign('component_id')->references('id')->on('landing_builder_components')->cascadeOnDelete();
        });

        Schema::create('landing_component_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('landing_component_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->longText('content');

            $table->foreign('landing_component_id', 'landing_component_id_trans')->on('landing_components')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landing_components');
        Schema::dropIfExists('landing_component_translations');
    }
};
