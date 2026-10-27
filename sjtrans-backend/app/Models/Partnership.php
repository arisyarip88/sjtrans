<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'logo_url',
        'website_url',
        'partnership_type', // corporate, travel_agent, hotel, fleet_partner, sponsor
        'status', // active, pending, inactive
    ];
}
