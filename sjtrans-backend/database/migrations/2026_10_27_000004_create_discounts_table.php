<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. SJTPROMO10, LIBURANSERU
            $table->string('title');
            $table->string('discount_type')->default('percentage'); // 'percentage' or 'fixed'
            $table->decimal('discount_value', 12, 2); // e.g. 10.00 (%) or 500000.00 (Rp)
            $table->decimal('min_transaction', 14, 2)->nullable();
            $table->decimal('max_discount', 14, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quota')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
