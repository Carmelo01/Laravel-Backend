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
        Schema::create('grade_rubrics', function (Blueprint $table) {
            $table->id();
            $table->double('grade');
            $table->unsignedBigInteger('rubrics_id');
            $table->unsignedBigInteger('faculty_id');
            $table->unsignedBigInteger('capsule_id');
            $table->foreign('rubrics_id')->references('id')->on('rubrics');
            $table->foreign('faculty_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('capsule_id')->references('id')->on('capsules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_rubrics');
    }
};
