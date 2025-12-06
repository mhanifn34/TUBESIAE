@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-xl text-center border border-green-200">
    <div class="text-green-500 mb-4"> <i class="fas fa-check-circle fa-4x"></i> </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil Dibuat!</h1>
    <p class="text-gray-600 mb-6">Terima kasih! Pesanan Anda sedang menunggu pembayaran.</p>

    @if(isset($order))
        <div class="text-left bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Ringkasan Pesanan</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-600">No. Pesanan:</span><span class="font-medium text-gray-900">{{ $order->order_number }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Tanggal:</span><span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span></div>
                 @if($order->items->first()?->post)
                    <div class="pt-3 border-t mt-3"><p class="font-semibold text-gray-800">{{ $order->items->first()->post->title }}</p><p class="text-gray-600">1 x Rp {{ number_format($order->items->first()->price, 0, ',', '.') }}</p></div>
                @endif
                <div class="flex justify-between pt-2"><span class="text-gray-600">Ongkir:</span><span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                 @if($order->shipment) <div class="flex justify-between"><span class="text-gray-600">Layanan:</span><span class="font-medium text-gray-900">{{ $order->shipment->courier_service }}</span></div> @endif
                <div class="flex justify-between font-bold text-xl pt-3 border-t mt-3"><span class="text-gray-800">Total:</span><span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg text-blue-800 text-sm mb-6"><h3 class="font-semibold mb-2">Instruksi Pembayaran</h3><p>Lakukan pembayaran sejumlah Rp {{ number_format($order->total_amount, 0, ',', '.') }} ke rekening Bank XYZ: 123-456-7890 a/n COMMORA.</p><p class="mt-2 text-xs">Pesanan akan diproses setelah pembayaran dikonfirmasi.</p></div>
    @endif
    <a href="{{ route('order.markPaid', $order->id) }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition text-sm font-medium">Kembali ke Beranda</a>

</div>
@endsection