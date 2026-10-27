<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order_position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order_position' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
