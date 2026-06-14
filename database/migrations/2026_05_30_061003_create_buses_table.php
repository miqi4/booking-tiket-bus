<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('plate_number')->unique();
            $table->unsignedSmallInteger('capacity');
            $table->string('seat_layout')->default('2-2');
            $table->string('seat_type')->default('standard');
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();

            // Data kursi disimpan langsung di sini sebagai JSON array.
            // Setiap elemen: { "seat_number": "1A", "row": 1, "column": 1, "type": "passenger"|"driver", "is_active": true }
            $table->json('seats')->nullable()->comment('Daftar kursi bus, disimpan sebagai JSON');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
