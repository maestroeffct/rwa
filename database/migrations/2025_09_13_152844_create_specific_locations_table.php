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
        Schema::create('specific_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('targetable_id')->unsigned();
            $table->string('targetable_type');
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('district_id')->unsigned()->nullable();
            $table->point('geo_center')->nullable();
            $table->text('address')->nullable();
            $table->string('zip_code')->nullable();

            $table->foreign('country_id')->on('regions')->references('id')->cascadeOnDelete();
            $table->foreign('province_id')->on('regions')->references('id')->nullOnDelete();
            $table->foreign('city_id')->on('regions')->references('id')->nullOnDelete();
            $table->foreign('district_id')->on('regions')->references('id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specific_locations');
    }
};
