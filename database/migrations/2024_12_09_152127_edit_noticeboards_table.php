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
        Schema::table('noticeboards', function (Blueprint $table) {
            $table->integer('sender_id')->unsigned()->nullable()->after('sender');
            $table->enum('sender_type', ['instructor', 'platform'])->default('instructor')->after('sender_id');

            $table->foreign('sender_id')->on('users')->references('id')->nullOnDelete();
        });
    }


};
