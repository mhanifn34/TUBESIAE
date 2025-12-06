<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
          $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('community_id')->constrained()->onDelete('cascade');

        // --- INI PERBAIKANNYA ---
        $table->string('type')->default('post'); // 1. Tambah kolom 'type'
        $table->string('title');
        $table->text('content')->nullable();
        $table->string('image')->nullable();     // 2. Ubah dari 'images' (json) ke 'image' (string)
        $table->string('video')->nullable();     // 3. Ubah dari 'video_url' ke 'video'

        // Kolom untuk Jual Beli
        $table->string('listing_type')->nullable(); // 'jual' atau 'beli'
        $table->string('condition')->nullable();    // 'baru' atau 'bekas'
        $table->decimal('price', 15, 2)->nullable();
        $table->string('status')->default('published'); // 'published', 'sold'
        // --- SELESAI PERBAIKAN ---

        // Kolom _count (opsional tapi bagus)
        $table->integer('likes_count')->default(0);
        $table->integer('comments_count')->default(0);
        $table->integer('views_count')->default(0);

        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};