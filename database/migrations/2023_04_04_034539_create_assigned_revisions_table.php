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
        Schema::create('assigned_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revision_id');
            $table->unsignedBigInteger('faculty_id');
            $table->string('title');
            $table->date('date-posted');
            $table->string('file_location');
            $table->string('comment');
            $table->double('grade');
            $table->integer('Update-Number');
            // $table->foreignId('revision_id')->constrained('revisions');
            // $table->foreignId('faculty_id')->constrained('users');
            $table->timestamps();
            $table->foreign('revision_id')->references('id')->on('revisions')->onDelete('cascade');
            $table->foreign('faculty_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_revisions');
    }
};
