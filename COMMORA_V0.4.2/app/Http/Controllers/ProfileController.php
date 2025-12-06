<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data statistik user
        $stats = [
            'pengikut' => 0,
            'mengikuti' => 0,
            'diabaikan' => 0,
            'threads' => $this->getUserThreadsCount($user->id),
            'posts' => $this->getUserPostsCount($user->id)
        ];
        
        return view('profile.index', compact('user', 'stats'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
        
        // Update user data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'bio' => $validated['bio'] ?? null,
        ]);
        
        return redirect()->route('profile.index')->with('success', 'Profile berhasil diupdate!');
    }
    
    private function getUserThreadsCount($userId)
    {
       
        return 1; 
    }
    
    private function getUserPostsCount($userId)
    {
       
        return 0; 
    }
}