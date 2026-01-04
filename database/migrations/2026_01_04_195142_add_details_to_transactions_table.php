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
            // Adding the missing columns required by TransactionController
            $table->foreignId('ticket_id')->nullable()->after('event_id')->constrained()->onDelete('cascade');
            $table->string('seat_number')->nullable()->after('ticket_id');
            $table->string('identity_number')->nullable()->after('seat_number');
            $table->string('face_image_path')->nullable()->after('identity_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropColumn(['ticket_id', 'seat_number', 'identity_number', 'face_image_path']);
        });
    }
};
