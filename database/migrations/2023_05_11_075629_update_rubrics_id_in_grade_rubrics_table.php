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
        Schema::table('grade_rubrics', function (Blueprint $table) {
            $table->longText('category')->after('grade');
            $table->longText('rubric')->after('category');
            $table->dropForeign(['rubrics_id']);
            $table->dropColumn('rubrics_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_rubrics', function (Blueprint $table) {
            $table->dropColumn('rubric');
            $table->dropColumn('category');
        });
    }
};
