<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giraffe_requests', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Foreign key to giraffes table
            $table->foreignId('giraffe_id')->constrained()->onDelete('cascade');

            // Optional request-specific fields
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giraffe_requests');
    }
};
