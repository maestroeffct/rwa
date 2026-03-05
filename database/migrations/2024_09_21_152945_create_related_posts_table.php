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
        Schema::create('related_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("targetable_id");
            $table->string("targetable_type");
            $table->integer("post_id")->unsigned();
            $table->integer("order")->nullable();

            $table->foreign("post_id")->references("id")->on("blog")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_posts');
    }
};
