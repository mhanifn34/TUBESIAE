<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // PASTIIN INI LENGKAP!
    protected $fillable = [
        'user_id',
        'community_id',
        'title',          // <--- Udah ada di DB
        'content',
        'image',          // <--- Udah ada di DB
        'video',          // <--- Udah ada di DB

        // --- TAMBAHAN KANTONG JUAL BELI ---
        'type',           // <-- Penting
        'listing_type',   // <-- Penting
        'condition',      // <-- Penting
        'price',          // <-- Penting
        
        // Kolom _count ini nggak perlu di-fillable
        // karena udah ada default 0 di DB
    ];

    /**
     * Relasi ke User (pemilik post).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Komunitas.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Relasi ke Likes (buat withCount).
     */
    public function likes()
    {
        // Asumsi lo punya Model PostReaction
        return $this->hasMany(PostReaction::class)->where('reaction_type', 'like');
    }

    /**
     * Relasi ke Comments (buat withCount).
     */
    public function comments()
    {
        // Asumsi lo punya Model Comment
        return $this->hasMany(Comment::class);
    }
}