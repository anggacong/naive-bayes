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
        Schema::create('naive_bayes_model', function (Blueprint $table) {
            $table->id();
            $table->enum('sumber', ['x', 'thread']);
            $table->enum('tipe', ['prior', 'likelihood']);
            $table->enum('sentimen', ['positif', 'negatif', 'netral']);
            $table->string('kata', 255)->nullable(); // null if tipe = prior
            $table->double('nilai_log');             // log-probability
            $table->timestamps();

            $table->index(['sumber', 'tipe', 'sentimen']);
            $table->index(['sumber', 'kata']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naive_bayes_model');
    }
};
