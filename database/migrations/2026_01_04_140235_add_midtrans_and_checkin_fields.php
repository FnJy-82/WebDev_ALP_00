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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('midtrans_booking_code')->nullable()->after('id');
            $table->string('snap_token')->nullable()->after('status');
            $table->string('payment_type')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('is_checked_in')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
