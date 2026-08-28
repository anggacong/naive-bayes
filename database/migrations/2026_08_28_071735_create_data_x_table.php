<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_x', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->text('teks_asli');
            $table->date('tanggal')->nullable();
            $table->enum('sentimen', ['positif', 'negatif', 'netral'])->default('netral');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_x');
    }
};
