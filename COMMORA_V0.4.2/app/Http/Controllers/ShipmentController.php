<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    /**
     * Menghitung ongkos kirim menggunakan API Biteship dengan Area ID dinamis.
     */
    public function calculate(Request $request)
    {
        // 1. Validasi input dari frontend (Area ID Asal & Tujuan, Berat, Kurir)
        $validated = $request->validate([
            'origin_area_id'      => 'required|string', // Terima Area ID Asal dari frontend
            'destination_area_id' => 'required|string', // Terima Area ID Tujuan dari frontend
            'weight'              => 'required|numeric|min:1',
            'courier_type'        => 'required|string', // Kurir yang dipilih
        ]);

        // 2. Ambil API Key Biteship dari .env
        $apiKey = env('BITESHIP_API_KEY');
        if (!$apiKey) {
            Log::error('BITESHIP_API_KEY not found in .env file.');
            return response()->json(['error' => 'Konfigurasi API ekspedisi tidak ditemukan.'], 500);
        }

        // 3. Endpoint API Biteship untuk Cek Ongkir
        $apiEndpoint = 'https://api.biteship.com/v1/rates/couriers';

        // 4. Siapkan Payload (Menggunakan Area ID dari request)
        $payload = [
            'origin_area_id'      => $validated['origin_area_id'],
            'destination_area_id' => $validated['destination_area_id'],
            'couriers'            => $validated['courier_type'],
            'items' => [
                [
                    'name'   => 'Produk', 'value'  => 10000, 'quantity' => 1,
                    'weight' => (int) $validated['weight'],
                ]
            ]
        ];

        Log::info('Attempting Biteship API Request (Final Dynamic):', ['payload' => $payload]);

        // 5. Kirim Request ke Biteship (Header: Authorization API_KEY)
        $response = Http::withHeaders(['Authorization' => $apiKey])
                       ->post($apiEndpoint, $payload);

        $responseData = $response->json();

        // 6. Penanganan Respons Biteship (Lengkap)
        if ($response->failed() || !$response->successful() || !isset($responseData['success']) || !$responseData['success']) {
            $errorMessage = $responseData['error'] ?? ($responseData['message'] ?? 'Gagal terhubung ke API Biteship.');
            Log::error('Biteship Rates API call failed.', [
                'status_code' => $response->status(), 'error_message' => $errorMessage, 'sent_payload' => $payload, 'full_response' => $responseData
            ]);
            // Pesan error spesifik berdasarkan respons
             if (str_contains(strtolower($errorMessage), 'balance')) { return response()->json(['error' => 'Saldo API tidak mencukupi.'], 500); }
             if (str_contains(strtolower($errorMessage), 'not found') || str_contains(strtolower($errorMessage), 'no courier available') || str_contains(strtolower($errorMessage),'parameter') || str_contains(strtolower($errorMessage), 'origin') || str_contains(strtolower($errorMessage), 'destination')) { return response()->json(['error' => 'Area asal/tujuan tidak valid atau kurir tidak tersedia.'], 400); }
            return response()->json(['error' => $errorMessage . ' (Code: ' . $response->status() . ')'], 500);
        }

        // 7. Ekstrak dan Format Hasil Ongkir dari 'pricing'
        $shippingOptions = $responseData['pricing'] ?? [];
        $formattedCosts = [];
        foreach ($shippingOptions as $option) {
             if (isset($option['courier_service_name']) && isset($option['price']) && isset($option['courier_name']) && isset($option['duration'])) {
                 $formattedCosts[] = [
                    'service' => $option['courier_service_name'],
                    'description' => $option['description'] ?? $option['courier_service_name'],
                    'cost' => [['value' => $option['price'], 'etd' => str_replace(' days',' hari',$option['duration']) ]],
                    'courier' => $option['courier_name']
                ];
            }
        }

        // 8. Handle Jika Tidak Ada Opsi Layanan Ditemukan (Respons sukses tapi pricing kosong)
        if (empty($formattedCosts)) {
             Log::warning('Biteship API success but no pricing options found.', ['response' => $responseData, 'payload' => $payload]);
             return response()->json([], 200); // Kembalikan array kosong jika tidak ada layanan, bukan error
        }

        // 9. Kirim Hasil ke Frontend
        return response()->json($formattedCosts);
    }
}