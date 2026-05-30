<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_route_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('bus_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('departure_at')->index();
            $table->dateTime('arrival_est')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('status')->default('active')->index();
            $table->unsignedSmallInteger('available_seats')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['bus_route_id', 'departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
