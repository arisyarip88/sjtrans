<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('fleet_id')->nullable()->constrained('bus_fleets')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_role_or_company')->nullable(); // e.g. "PT Astra International"
            $table->tinyInteger('rating')->default(5); // 1 - 5
            $table->text('comment');
            $table->string('photo_url')->nullable();
            $table->boolean('is_approved')->default(false); // Moderasi Admin
            $table->boolean('is_featured')->default(false); // Ditampilkan di section unggulan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
