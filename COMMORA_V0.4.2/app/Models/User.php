<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'google_id',
        'avatar',
        'bio'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * Update password user
     */
    public function updatePassword($newPassword)
    {
        $this->password = Hash::make($newPassword);
        return $this->save();
    }
    

    // Relasi
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_user')
            ->withTimestamps();
            
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    // Helper functions
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function follow($userId)
    {
        if (!$this->isFollowing($userId)) {
            $this->following()->attach($userId);
        }
    }

    public function unfollow($userId)
    {
        $this->following()->detach($userId);
    }

    public function isMemberOf($communityId)
    {
        return $this->communities()->where('community_id', $communityId)->exists();
    }

    /**
     * Get jumlah followers
     */
    public function getFollowersCount()
    {
        return $this->followers()->count();
    }

    /**
     * Get jumlah following
     */
    public function getFollowingCount()
    {
        return $this->following()->count();
    }

    /**
     * Get total posts
     */
    public function getPostsCount()
    {
        return $this->posts()->count();
    }
}
