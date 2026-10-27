<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fleet_id',
        'customer_name',
        'customer_role_or_company',
        'rating',
        'comment',
        'photo_url',
        'is_approved',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
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
}
