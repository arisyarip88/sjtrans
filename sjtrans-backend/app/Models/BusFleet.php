<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusFleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'capacity',
        'seat_layout',
        'price_per_day',
        'facilities',
        'specifications',
        'thumbnail_url',
        'gallery_urls',
        'description',
        'status', // available, booked, maintenance
    ];

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'specifications' => 'array',
            'gallery_urls' => 'array',
            'price_per_day' => 'decimal:2',
            'capacity' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'fleet_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'fleet_id');
    }
}
