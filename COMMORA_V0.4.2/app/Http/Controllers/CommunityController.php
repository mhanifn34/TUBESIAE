<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::withCount('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('communities.index', compact('communities'));
    }

    public function myCommunities(Request $request)
{
    $user = $request->user();
    $communities = $user->communities()->withCount('members')->get();

    return view('communities.my', compact('communities'));
}


    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities,name',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $slug = Str::slug($validated['name']);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('communities', 'public');
        }

        $community = Community::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
            'members_count' => 1 // Creator otomatis jadi member
        ]);

        // Auto-join creator as member
        $community->members()->attach(Auth::id());

        return redirect()->route('communities.show', $community->slug)
            ->with('success', 'Komunitas berhasil dibuat!');
    }
   

    public function show(Community $community)
    {
        $posts = $community->posts()
            ->with('user')
            ->latest()
            ->get();
        
        return view('communities.show', compact('community', 'posts'));
    }

    public function edit($slug)
    {
        $community = Community::where('slug', $slug)->firstOrFail();
        
        // Only creator can edit
        if ($community->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, $slug)
    {
        $community = Community::where('slug', $slug)->firstOrFail();
        
        // Only creator can update
        if ($community->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities,name,' . $community->id,
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($community->image) {
                Storage::disk('public')->delete($community->image);
            }
            $validated['image'] = $request->file('image')->store('communities', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        
        $community->update($validated);

        return redirect()->route('communities.show', $community->slug)
            ->with('success', 'Komunitas berhasil diupdate!');
    }

    public function destroy($slug)
    {
        $community = Community::where('slug', $slug)->firstOrFail();
        
        // Only creator can delete
        if ($community->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image
        if ($community->image) {
            Storage::disk('public')->delete($community->image);
        }

        $community->delete();

        return redirect()->route('communities.index')
            ->with('success', 'Komunitas berhasil dihapus!');
    }

    public function join(Community $community)
    {
        $userId = Auth::id();
        
        // Cek apakah sudah join
        if (!$community->members()->where('user_id', $userId)->exists()) {
            $community->members()->attach($userId);
            $community->increment('members_count');
            
            return back()->with('success', 'Berhasil bergabung dengan komunitas!');
        }
        
        return back()->with('info', 'Anda sudah menjadi anggota komunitas ini.');
    }

    public function leave(Community $community)
    {
        $userId = Auth::id();
        
        // Cek apakah memang member
        if ($community->members()->where('user_id', $userId)->exists()) {
            $community->members()->detach($userId);
            $community->decrement('members_count');
            
            return back()->with('success', 'Berhasil keluar dari komunitas!');
        }
        
        return back()->with('info', 'Anda bukan anggota komunitas ini.');
    }

    /**
     * Menampilkan komunitas yang telah diikuti user
     */
    public function listCommunity()
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // 1. Ambil komunitas yang telah diikuti oleh user
        // Relasi 'communities' harus didefinisikan di model User
        $joinedCommunities = $user->communities; 

        // 2. Kirim data ke view baru
        return view('communities.joined_list', compact('joinedCommunities'));
    }
}