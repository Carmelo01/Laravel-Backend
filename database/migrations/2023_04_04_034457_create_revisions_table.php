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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('capsule_id');
            $table->string('title');
            // $table->date('date-posted');
            $table->string('file_location');
            // $table->foreignId('capsule_id')->constrained('capsules')->onDelete('cascade');
            $table->string('comment');
            // $table->double('grade');
            // $table->integer('Update-Number');
            $table->timestamps();
            $table->foreign('capsule_id')->references('id')->on('capsules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
