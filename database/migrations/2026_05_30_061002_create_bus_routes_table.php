<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_city_id')->constrained('cities')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('destination_city_id')->constrained('cities')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('origin_terminal_id')->nullable()->constrained('bus_terminals')->nullOnDelete();
            $table->foreignId('destination_terminal_id')->nullable()->constrained('bus_terminals')->nullOnDelete();
            $table->unsignedInteger('distance_km')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['origin_city_id', 'destination_city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
