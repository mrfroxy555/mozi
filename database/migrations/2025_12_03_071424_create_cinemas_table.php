<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nagy terem, Közepes terem, Kisterem
            $table->integer('capacity'); // 120, 60, 20
            $table->integer('rows'); // Sorok száma
            $table->integer('seats_per_row'); // Székek száma soronként
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cinemas');
    }
};