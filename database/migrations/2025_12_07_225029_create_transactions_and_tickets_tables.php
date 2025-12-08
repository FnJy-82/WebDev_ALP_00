<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Transaksi (Mencatat Pembelian)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->dateTime('transaction_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('success'); // pending, success, failed
            $table->string('snap_token')->nullable(); // Untuk payment gateway nanti
            $table->timestamps();
        });

        // 2. Tabel Tiket (Hasil Pembelian / Identity Binding)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pemilik Tiket
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            
            $table->string('seat_number'); // Hasil Seat Generator
            $table->string('face_photo_path'); // Foto Selfie saat War
            $table->string('status')->default('active'); // active, checked_in, refunded
            $table->string('qr_code_hash')->unique(); // String unik untuk QR
            $table->timestamps();
        });

        // 3. Tabel Wallet Mutation (Riwayat Saldo)
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // credit (masuk), debit (keluar)
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('transactions');
    }
};