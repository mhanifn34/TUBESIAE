<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
// Kita UDAH NGGAK PERLU Cache di sini
// use Illuminate\Support\Facades\Cache; 

class HomeController extends Controller
{
    public function index()
    {
        // --- PERBAIKAN 1: PAKE PAGINATE! ---
        $posts = Post::with(['user', 'community']) 
                       ->latest()
                       ->paginate(15); 
        
        // --- BLOK INI KITA HAPUS KARENA UDAH DIHANDLE SAMA AppServiceProvider ---
        // $modalCommunities = Cache::remember('all_communities_list', 3600, function () {
        //     return Community::select('id', 'name')->orderBy('name')->get();
        // });
        // -----------------------------------------------------------------

        // B. Buat Sidebar (Contoh: 5 komunitas terpopuler)
        $sidebarCommunities = Community::withCount('members')->orderBy('members_count', 'desc')->take(5)->get();
        
        // Kirim ke view home
        return view('home', [
            'posts' => $posts,
            // $modalCommunities udah otomatis dikirim dari AppServiceProvider
            'sidebarCommunities' => $sidebarCommunities,
        ]);
    }
}