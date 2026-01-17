<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->dateTime('loan_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'borrowed', 'returned', 'overdue'])->default('pending');
            $table->foreignId('approved_by')->constrained('users')->nullOnDelete();
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->index('user_id');
            $table->index('book_id');
            $table->index('status');
            $table->index('due_date');
            $table->softDeletes();
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