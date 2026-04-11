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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained();
            $table->foreignId('user_id')->constrained(); // The member borrowing it

            $table->timestamp('borrowed_at')->useCurrent();
            $table->timestamp('due_date');
            $table->timestamp('returned_at')->nullable();

            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->decimal('fine_amount', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
