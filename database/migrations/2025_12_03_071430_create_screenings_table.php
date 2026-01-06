<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->foreignId('cinema_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time'); // Automatikusan számított
            $table->boolean('is_visible')->default(true); // Műsorrend láthatósága
            $table->timestamps();
            
            $table->index(['cinema_id', 'start_time']);
            $table->index(['start_time', 'is_visible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};