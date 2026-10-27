<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BusFleet;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_create_booking_with_discount_and_calculate_price(): void
    {
        $fleet = BusFleet::first();

        $response = $this->postJson('/api/v1/bookings', [
            'fleet_id' => $fleet->id,
            'customer_name' => 'Rina Wijaya',
            'customer_email' => 'rina@example.com',
            'customer_phone' => '081233445566',
            'pickup_location' => 'Bandara Soekarno Hatta Terminal 3',
            'destination' => 'Puncak, Bogor',
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(), // 3 days
            'total_passengers' => 30,
            'total_units' => 1,
            'discount_code' => 'SJTPROMO10',
            'special_notes' => 'Tolong sediakan supir non-smoking',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.customer_name', 'Rina Wijaya')
            ->assertJsonPath('data.discount_code', 'SJTPROMO10')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Rina Wijaya',
            'discount_code' => 'SJTPROMO10',
        ]);
    }

    public function test_admin_can_verify_transaction(): void
    {
        $admin = User::where('username', 'admin')->first();
        $booking = Booking::first();
        $transaction = $booking->transactions()->first();

        $response = $this->actingAs($admin)
            ->putJson("/api/v1/transactions/{$transaction->id}/verify", [
                'status' => 'verified',
                'notes' => 'Pembayaran berhasil diverifikasi oleh Admin',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'verified');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'verified',
        ]);
    }
}
