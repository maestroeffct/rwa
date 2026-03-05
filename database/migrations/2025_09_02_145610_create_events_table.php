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
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', \App\Enums\EventEnums::allTypes)->default(\App\Enums\EventEnums::ONLINE);
            $table->string('slug')->unique();
            $table->integer('creator_id')->unsigned();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('icon')->nullable();
            $table->enum('video_demo_source', \App\Models\File::$fileSources)->nullable();
            $table->string('video_demo')->nullable();
            $table->integer('sales_count_number')->unsigned()->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->integer('purchase_limit_count')->unsigned()->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->bigInteger('start_date')->unsigned()->nullable();
            $table->bigInteger('end_date')->unsigned()->nullable();
            $table->bigInteger('sales_end_date')->unsigned()->nullable();
            $table->boolean('enable_countdown')->default(false);
            $table->enum('countdown_time_reference', ['start_date', 'sales_end_date'])->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('support')->default(false);
            $table->boolean('certificate')->default(false);
            $table->boolean('private')->default(false);

            $table->text('message_for_reviewer')->nullable();
            $table->enum('status', ['publish', 'draft', 'pending', 'unpublish', 'canceled', 'rejected'])->default('draft');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('updated_at')->unsigned();

            $table->foreign('creator_id')->on('users')->references('id')->cascadeOnDelete();
            $table->foreign('category_id')->on('categories')->references('id')->cascadeOnDelete();
        });

        Schema::create('event_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id')->unsigned();
            $table->string('locale', 191)->index();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            $table->foreign('event_id')->on('events')->references('id')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_translations');
    }
};
