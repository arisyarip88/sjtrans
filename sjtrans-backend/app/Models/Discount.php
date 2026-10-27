<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'discount_type', // percentage or fixed
        'discount_value',
        'min_transaction',
        'max_discount',
        'start_date',
        'end_date',
        'quota',
        'used_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_transaction' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'quota' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isValid(float $subtotal = 0): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = Carbon::today();
        if ($today->lt($this->start_date) || $today->gt($this->end_date)) {
            return false;
        }

        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return false;
        }

        if ($this->min_transaction !== null && $subtotal < $this->min_transaction) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid($subtotal)) {
            return 0.0;
        }

        if ($this->discount_type === 'percentage') {
            $deduction = ($this->discount_value / 100.0) * $subtotal;
            if ($this->max_discount !== null && $deduction > $this->max_discount) {
                return (float) $this->max_discount;
            }
            return (float) $deduction;
        }

        // Fixed amount
        return min((float) $this->discount_value, $subtotal);
    }
}
