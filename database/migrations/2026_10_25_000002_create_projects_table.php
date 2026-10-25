<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_th');
            $table->string('title_en')->nullable();
            $table->text('summary_th')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('body_th')->nullable();
            $table->text('body_en')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('area_sqm')->nullable();
            $table->string('status', 20)->default('planning');
            $table->boolean('is_concept')->default(false);
            $table->string('cover_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['published_at', 'is_concept', 'sort_order']);
        });

        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_th')->nullable();
            $table->string('alt_en')->nullable();
            $table->string('caption_th')->nullable();
            $table->string('caption_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_images');
        Schema::dropIfExists('projects');
    }
};
