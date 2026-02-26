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
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('source_id');
            $table->string('category')->nullable()->index();
            $table->string('type')->index();
            $table->json('content')->nullable();
            $table->string('status')->index();
            $table->timestamps();
            $table->unique(['source', 'source_id']);
            $table->index(['type', 'category']);
        });

        Schema::create('joke_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('joke_id')->constrained('jokes')->cascadeOnDelete();
            $table->vector('embedding_768', 768)->nullable()->index();
            $table->string('model');
            $table->timestamps();
            $table->unique(['joke_id', 'model']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jokes');
        Schema::dropIfExists('joke_embeddings');
    }
};
