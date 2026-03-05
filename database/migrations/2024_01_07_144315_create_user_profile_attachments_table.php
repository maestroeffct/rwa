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
        Schema::create('user_profile_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('file_type', \App\Models\File::$fileTypes);
            $table->string('attachment')->nullable();
            $table->bigInteger('created_at')->unsigned();

            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
        });

        Schema::create('user_profile_attachment_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_profile_attachment_id');
            $table->string('locale', 191)->index();
            $table->string('title');
            $table->text('description')->nullable();

            $table->foreign('user_profile_attachment_id', 'user_profile_attachment_id_trans')->on('user_profile_attachments')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile_attachments');
    }
};
