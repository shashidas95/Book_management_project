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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // Multi-Tenancy: Tie the book to a specific Library/Tenant
            $table->foreignId('library_id')->constrained('libraries')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');            $table->string('title')->index(); // Added index for faster searching
            $table->string('author')->index(); // Added index for filtering by author

            // Professional ISBN: 13 digits usually, unique per tenant or globally
            $table->string('isbn')->unique();

            $table->year('published_year')->nullable();

            // Logic: available_copies should never exceed total_copies
            $table->unsignedInteger('available_copies')->default(1);
            $table->unsignedInteger('total_copies')->default(1);
            
            $table->string('cover_image')->nullable();
            // Status: Useful for soft-deletes or "archived" books
            $table->boolean('is_active')->default(true);
            $table->softDeletes(); // $table->timestamp('deleted_at')

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
