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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('client')->nullable();
            $table->string('industry')->nullable();
            $table->string('year', 4)->nullable();
            $table->text('excerpt')->nullable();
            $table->json('results')->nullable();
            $table->json('tags')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('project_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status');
            $table->json('deleted_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
