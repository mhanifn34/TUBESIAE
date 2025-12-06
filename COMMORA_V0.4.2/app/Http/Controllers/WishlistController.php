<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // <-- Jangan lupa import model Post
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = Wishlist::where('user_id', $user->id)->with('post')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Post $post) // <-- Perbaikan ada di sini, tambahkan "Post $post"
    {
        $user = Auth::user();

        // Cek apakah item sudah ada di wishlist
        $existingItem = Wishlist::where('user_id', $user->id)
                                ->where('post_id', $post->id)
                                ->first();

        if ($existingItem) {
            return back()->with('info', 'Item ini sudah ada di wishlist Anda.');
        }

        // Jika belum ada, tambahkan ke wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'Berhasil ditambahkan ke wishlist!');
    }
}