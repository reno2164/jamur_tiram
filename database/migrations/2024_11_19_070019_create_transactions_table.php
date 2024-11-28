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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_code')->unique();
            $table->decimal('total_price', 15, 2);
            $table->string('payment_method')->nullable();
            $table->enum('status', ['Sedang Dikemas','Belum Dibayar', 'Selesai', 'Gagal'])->default('Sedang Dikemas');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
