<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Widens columns that could receive more than 255 characters:
 * - blogs.excerpt: the admin form allows up to 500 characters.
 * - enquiries.source_url / activity_logs.url: full URLs with long query strings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->change();
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->text('source_url')->nullable()->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->text('url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('excerpt')->nullable()->change();
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('source_url')->nullable()->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('url')->nullable()->change();
        });
    }
};
