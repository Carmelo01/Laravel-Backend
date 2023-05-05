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
        Schema::create('assigned_capsules', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('faculty_id')->constrained('users');
            // $table->foreignId('capsule_id')->constrained('capsules');
            $table->unsignedBigInteger('faculty_id');
            $table->unsignedBigInteger('capsule_id');
            // $table->string('comment'); // create new table for comment
            $table->double('grade')->nullable();
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreign('faculty_id')->references('id')->on('users');
            $table->foreign('capsule_id')->references('id')->on('capsules');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_capsules');
    }
};
