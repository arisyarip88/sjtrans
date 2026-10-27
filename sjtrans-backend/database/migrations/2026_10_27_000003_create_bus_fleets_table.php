<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name'); // e.g. Big Bus SHD Scania K410IB, HiAce Premio Luxury
            $table->string('slug')->unique();
            $table->integer('capacity'); // 14, 19, 31, 35, 50, 59 seats
            $table->string('seat_layout')->default('2-2'); // 2-1, 2-2, 2-3
            $table->decimal('price_per_day', 12, 2);
            $table->json('facilities')->nullable(); // AC, TV, Karaoke, Toilet, Wifi, Reclining Seats
            $table->json('specifications')->nullable(); // Chassis, Engine, Dimensi
            $table->string('thumbnail_url')->nullable();
            $table->json('gallery_urls')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // available, booked, maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_fleets');
    }
};
