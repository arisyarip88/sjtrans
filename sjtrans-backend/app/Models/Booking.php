<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'fleet_id',
        'discount_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pickup_location',
        'destination',
        'start_date',
        'end_date',
        'total_days',
        'total_passengers',
        'total_units',
        'subtotal',
        'discount_code',
        'discount_amount',
        'total_price',
        'special_notes',
        'status', // pending, confirmed, in_trip, completed, cancelled
        'payment_status', // unpaid, down_payment, paid, refunded
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_days' => 'integer',
            'total_passengers' => 'integer',
            'total_units' => 'integer',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(BusFleet::class, 'fleet_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
