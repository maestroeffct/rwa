<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            // Negative mark for multiple-choice questions; nullable means no negative marking
            if (!Schema::hasColumn('quizzes_questions', 'negative_grade')) {
                $table->integer('negative_grade')->nullable()->after('grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes_questions', 'negative_grade')) {
                $table->dropColumn('negative_grade');
            }
        });
    }
};


