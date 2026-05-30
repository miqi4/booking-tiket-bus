<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('seat_number', 10);
            $table->unsignedSmallInteger('row');
            $table->unsignedSmallInteger('column');
            $table->string('type')->default('passenger');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['bus_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
