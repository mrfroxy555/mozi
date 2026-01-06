<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // NULL = vendég
            $table->foreignId('screening_id')->constrained()->onDelete('cascade');
            $table->string('guest_name')->nullable(); // Vendég neve
            $table->string('guest_email')->nullable(); // Vendég email
            $table->string('booking_code')->unique(); // Egyedi foglalási kód
            $table->integer('total_price'); // Végösszeg
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};