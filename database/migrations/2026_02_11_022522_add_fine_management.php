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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->unique();
            $table->enum('fine_status', ['paid', 'unpaid'])->nullable();
            $table->enum('payment_method', ['cash', 'qris'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('fine_token')->nullable()->unique();
            $table->enum('fine_status', ['paid', 'unpaid'])->nullable();
            $table->enum('fine_method', ['cash', 'qris'])->nullable();
        });
    }
};