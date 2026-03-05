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
        Schema::create('meeting_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned();
            $table->string('icon')->nullable();
            $table->integer('duration')->unsigned();
            $table->enum('duration_type', ['day', 'week', 'month', 'year'])->default('day');
            $table->integer('sessions')->unsigned();
            $table->integer('session_duration')->unsigned()->comment("Each Session duration (Minutes)");
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('discount')->unsigned()->nullable();
            $table->boolean('enable')->default(false);
            $table->bigInteger('created_at')->unsigned();

            $table->foreign('creator_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('meeting_package_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('meeting_package_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('title');

            $table->foreign('meeting_package_id')->on('meeting_packages')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_packages');
        Schema::dropIfExists('meeting_package_translations');
    }
};
