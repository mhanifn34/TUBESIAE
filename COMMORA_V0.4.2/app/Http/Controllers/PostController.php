<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Generally not used if showing posts on home/community page
        return redirect()->route('home');
    }

    /**
     * Show the form for creating a new resource.
     * (Not typically used if using a modal)
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created regular post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:10240', // Max 10MB
            'video' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200', // Max 50MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('posts/videos', 'public');
        }

        Auth::user()->posts()->create([
            'community_id' => $validated['community_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'video' => $videoPath,
            'type' => 'post', // Set type explicitly for regular posts
        ]);

        return redirect()->route('home')->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Store a newly created listing post (Jual Beli) in storage.
     */
    public function storeListing(Request $request)
    {
        $validated = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'listing_type' => 'required|in:jual,beli',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'condition' => 'nullable|in:baru,bekas', // Optional condition
            'image' => 'nullable|image|max:10240', // Max 10MB for listing image
            'content' => 'nullable|string', // Description is optional for listings
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
        }

        // Create the listing post
        Auth::user()->posts()->create([
            'community_id' => $validated['community_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? '', // Default to empty string if null
            'image' => $imagePath,
            'type' => 'listing', // Set type explicitly
            'listing_type' => $validated['listing_type'],
            'price' => $validated['price'],
            'condition' => $validated['condition'] ?? null, // Save condition if provided
            'status' => 'published', // Default status for new listings
        ]);

        // --- PERBAIKAN REDIRECT ---
        // Pastikan hanya ada return ini di akhir method
        return redirect()->route('home')->with('success', 'Listing berhasil diposting!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // You might want to load relationships here if needed for a detail view
        // $post->load('user', 'community', 'comments.user');
        // return view('posts.show', compact('post')); // Example view
        return redirect()->route('home'); // Or just redirect if no detail view exists
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Authorization check
        if (! Gate::allows('update-post', $post)) {
            abort(403);
        }
        // return view('posts.edit', compact('post')); // Example view
        return redirect()->route('home'); // Or redirect if no edit view exists
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Authorization check
        if (! Gate::allows('update-post', $post)) {
            abort(403);
        }

        // Validation rules similar to store, adjust as needed
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required_if:type,post|nullable|string', // Content required for regular posts
            // Add other fields like price, condition for listings if editable
            'image' => 'nullable|image|max:10240',
            'video' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200',
        ]);

        // Handle image update (delete old if new one uploaded)
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts/images', 'public');
        } elseif ($request->input('remove_image')) { // Add a checkbox in form to remove image
             if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = null;
        }


        // Handle video update (similar logic)
        if ($request->hasFile('video')) {
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            $validated['video'] = $request->file('video')->store('posts/videos', 'public');
        } elseif ($request->input('remove_video')) {
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            $validated['video'] = null;
        }

        // Update the post (only validated fields)
        $post->update($validated);

        return redirect()->route('home')->with('success', 'Post berhasil diperbarui!');
        // Or redirect back to the post detail page if you have one
        // return redirect()->route('posts.show', $post)->with('success', 'Post berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Authorization check
        if (! Gate::allows('delete-post', $post)) {
            abort(403);
        }

        // Delete associated files if they exist
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        if ($post->video) {
            Storage::disk('public')->delete($post->video);
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post berhasil dihapus!');
    }
}
