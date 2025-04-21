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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('audio_url');
            $table->integer('duration_in_seconds');
            $table->string('image_url')->nullable();
            $table->integer('episode_number');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at');
            $table->timestamps();
            
            // Indexes for faster searching and filtering
            $table->index('slug');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['podcast_id', 'episode_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};