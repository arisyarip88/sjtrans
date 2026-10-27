<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // SJT-202610-0001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('fleet_id')->constrained('bus_fleets')->onDelete('cascade');
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('pickup_location');
            $table->text('destination');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->integer('total_passengers')->default(1);
            $table->integer('total_units')->default(1);
            $table->decimal('subtotal', 14, 2);
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('total_price', 14, 2);
            $table->text('special_notes')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, in_trip, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, down_payment, paid, refunded
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('invoice_number')->unique(); // INV-SJT-202610-0001
            $table->decimal('amount', 14, 2);
            $table->string('payment_type')->default('down_payment'); // down_payment, full_payment, remaining
            $table->string('payment_method')->default('bank_transfer'); // bank_transfer, qris, cash
            $table->string('payment_proof_url')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('bookings');
    }
};
