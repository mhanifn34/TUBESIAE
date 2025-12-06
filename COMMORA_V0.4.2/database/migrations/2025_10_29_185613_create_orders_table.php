<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke pembeli (user)
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');

            // Informasi pelanggan tambahan (nama & nomor HP bisa saja beda dengan user)
            $table->string('name'); // nama penerima
            $table->string('phone'); // nomor telepon penerima

            // Alamat pengiriman lengkap
            $table->text('address'); // alamat detail
            $table->string('shipping_service')->nullable(); // jenis layanan pengiriman (optional)

            // Kuantitas total item dalam order
            $table->integer('qty')->default(1);

            // Informasi harga & biaya
            $table->decimal('total_amount', 15, 2); // total harga barang
            $table->decimal('shipping_cost', 15, 2)->default(0); // ongkir

            // Nomor unik pesanan
            $table->string('order_number')->unique();

            // Status: Unpaid, Paid, Processing, Completed, Cancelled
            $table->enum('status', ['Unpaid', 'Paid', 'Processing', 'Completed', 'Cancelled'])->default('Unpaid');

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
