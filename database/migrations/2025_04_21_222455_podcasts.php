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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('author');
            $table->string('image_url');
            $table->string('website_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('total_episodes')->default(0);
            $table->timestamp('last_published_at')->nullable();
            $table->timestamps();
            
            // Indexes for faster searching and filtering
            $table->index('slug');
            $table->index('is_featured');
            $table->index('last_published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};