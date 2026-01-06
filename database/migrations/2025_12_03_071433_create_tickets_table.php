<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade');
            $table->foreignId('screening_id')->constrained()->onDelete('cascade');
            $table->string('qr_code')->unique(); // QR kód egyedi azonosító
            $table->integer('price'); // Jegy ára
            $table->boolean('is_used')->default(false); // Beváltva-e
            $table->timestamps();
            
            $table->unique(['screening_id', 'seat_id']); // Egy ülés csak egyszer foglalható vetítésenként
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};