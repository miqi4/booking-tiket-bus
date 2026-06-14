<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('seat_number', 10)->comment('Nomor kursi, merujuk ke buses.seats JSON');
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('ticket_code', 40)->unique();
            $table->timestamp('boarded_at')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
