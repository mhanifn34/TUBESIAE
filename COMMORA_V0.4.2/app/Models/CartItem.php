<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'user_id',
        'post_id',
        'quantity',
    ];

    // Relasi ke User (pemilik item)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Post (barang)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}