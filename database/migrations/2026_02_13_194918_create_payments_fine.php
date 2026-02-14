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
        Schema::create('payments_fine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('external_id')->unique();
            $table->string('xendit_invoice_id')->nullable();
            $table->integer('amount');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending'); // pending, paid, failed
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            $table->index('loan_id');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_fine');
    }
};