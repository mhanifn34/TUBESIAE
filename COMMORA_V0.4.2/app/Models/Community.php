<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'user_id',
        'members_count'
    ];

    // PENTING: Beritahu Laravel untuk binding pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_user')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Helper method
    public function isMember($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->members()->where('user_id', $userId)->exists();
    }
}