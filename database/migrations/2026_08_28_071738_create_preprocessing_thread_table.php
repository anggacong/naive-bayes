<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preprocessing_thread', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_thread_id')->constrained('data_thread')->onDelete('cascade');
            $table->text('hasil_cleaning')->nullable();
            $table->text('hasil_case_folding')->nullable();
            $table->json('hasil_tokenizing')->nullable();
            $table->text('hasil_repeated_char')->nullable();
            $table->text('hasil_normalisasi')->nullable();
            $table->text('hasil_stopword')->nullable();
            $table->text('hasil_stemming')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preprocessing_thread');
    }
};
