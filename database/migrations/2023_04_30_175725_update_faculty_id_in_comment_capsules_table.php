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
        Schema::table('comment_capsules', function (Blueprint $table) {
            $table->unsignedBigInteger('faculty_id')->nullable()->change();
            $table->foreign('faculty_id')->references('id')->on('users')->onDelete('set null')->name('comment_capsules_faculty_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_capsules', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->unsignedBigInteger('faculty_id')->change();
        });
    }
};
