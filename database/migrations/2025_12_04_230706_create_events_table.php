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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Relasi ke User (Organizer)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relasi ke Venue (Lokasi) - Jika venue dihapus, event jangan dihapus (set null)
            $table->foreignId('venue_id')->nullable()->constrained()->onDelete('set null');

            $table->string('title');
            $table->text('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('banner_image')->nullable();

            // Status: draft, active, finished, canceled
            $table->string('status')->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
