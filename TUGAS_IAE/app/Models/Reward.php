<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'title', 'description', 'promo_type', 'discount_percentage', 
        'max_cashback', 'valid_until', 'is_active'
    ];

    protected $casts = [
        'max_cashback' => 'decimal:2',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];
}