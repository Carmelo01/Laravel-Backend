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
        Schema::create('capsules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->string('file_path');
            $table->string('description');
            // $table->date('date_posted');
            // $table->foreignId('author_id')->constrained('users');
            $table->string('status')->default('0');
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->date('date_posted')->default(now()->toDateTimeString());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capsules');
    }
};
