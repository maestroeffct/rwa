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
        Schema::create('role_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('role_id');
            $table->string('locale', 191)->index();
            $table->string('caption');

            $table->foreign('role_id')->on('roles')->references('id')->onDelete('cascade');
        });


        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('caption');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_translations');
    }
};
