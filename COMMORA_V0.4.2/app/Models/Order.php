<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke pembeli
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Relasi ke item yang dibeli
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke data pengiriman
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
