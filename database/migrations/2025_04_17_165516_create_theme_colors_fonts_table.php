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
        Schema::create('theme_colors_fonts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['color', 'font']);
            $table->string('title');
            $table->text('content')->nullable();
            $table->bigInteger('created_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_colors_fonts');
    }
};
