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
        Schema::create('unigram_thread', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_thread_id')->constrained('data_thread')->onDelete('cascade');
            $table->string('kata', 255);
            $table->double('tf')->default(0);
            $table->unsignedInteger('df')->default(0);
            $table->double('idf')->default(0);
            $table->double('tfidf')->default(0);
            $table->enum('sentimen', ['positif', 'negatif', 'netral']);
            $table->timestamps();

            $table->index(['data_thread_id', 'kata']);
            $table->index('kata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unigram_thread');
    }
};
