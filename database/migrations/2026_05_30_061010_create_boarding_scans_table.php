<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('passenger_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('qr_payload');
            $table->string('status')->default('valid')->index();
            $table->timestamp('scanned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_scans');
    }
};
