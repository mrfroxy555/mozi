<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VIP, Normál, Diák, stb.
            $table->integer('price'); // Ár forintban
            $table->string('color')->default('#6b7280'); // Szín a térképen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_categories');
    }
};