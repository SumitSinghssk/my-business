<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('excerpt')->nullable();
            $table->json('highlights')->nullable();
            $table->json('tags')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status');
            $table->json('deleted_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
