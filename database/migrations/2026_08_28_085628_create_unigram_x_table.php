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
        Schema::create('unigram_x', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_x_id')->constrained('data_x')->onDelete('cascade');
            $table->string('kata', 255);
            $table->double('tf')->default(0);
            $table->unsignedInteger('df')->default(0);
            $table->double('idf')->default(0);
            $table->double('tfidf')->default(0);
            $table->enum('sentimen', ['positif', 'negatif', 'netral']);
            $table->timestamps();

            $table->index(['data_x_id', 'kata']);
            $table->index('kata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unigram_x');
    }
};
