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
        Schema::create('naive_bayes_hasil', function (Blueprint $table) {
            $table->id();
            $table->enum('sumber', ['x', 'thread']);
            $table->unsignedBigInteger('data_id');   // ID di data_x atau data_thread
            $table->enum('split', ['train', 'test']); // bagian training atau testing
            $table->enum('sentimen_asli', ['positif', 'negatif', 'netral']);
            $table->enum('sentimen_prediksi', ['positif', 'negatif', 'netral'])->nullable();
            $table->boolean('benar')->nullable();     // true jika prediksi == asli
            $table->timestamps();

            $table->index(['sumber', 'split']);
            $table->index(['sumber', 'data_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naive_bayes_hasil');
    }
};
