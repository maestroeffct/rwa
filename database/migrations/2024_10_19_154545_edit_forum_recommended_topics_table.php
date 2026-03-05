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
        Schema::table('forum_recommended_topics', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `forum_recommended_topics` DROP COLUMN `title`");
        });

        Schema::create('forum_recommended_topic_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('forum_recommended_topic_id');
            $table->string('locale', 191)->index();
            $table->string('title');
            $table->text('subtitle');

            $table->foreign('forum_recommended_topic_id', 'forum_recommended_topic_id_trans')->on('forum_recommended_topics')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_recommended_topic_translations');
    }

};
