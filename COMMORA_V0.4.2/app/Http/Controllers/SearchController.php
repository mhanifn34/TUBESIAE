<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan view kosong atau redirect sementara
        // return view('search.index', ['posts' => [], 'query' => $request->input('q')]);
        return "Halaman Pencarian (Belum diimplementasi)"; // Ganti dengan view jika sudah ada
    }
}