<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'followers'; // nama tabel pivot yang digunakan

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    public $timestamps = true; // kalau tabel followers pakai timestamps

    /**
     * User yang melakukan follow.
     * Contoh: $follow->follower->username
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * User yang diikuti (yang difollow).
     * Contoh: $follow->following->username
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
