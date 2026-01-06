<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_category_id')->constrained()->onDelete('cascade');
            $table->integer('row_number'); // Sor száma
            $table->integer('seat_number'); // Szék száma a sorban
            $table->string('seat_label'); // Pl: "A1", "B5"
            $table->timestamps();
            
            $table->unique(['cinema_id', 'row_number', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};