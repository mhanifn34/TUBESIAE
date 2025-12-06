<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapsController extends Controller
{
    /**
     * Mencari Area ID Biteship berdasarkan input teks (query).
     */
    public function searchArea(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $apiKey = env('BITESHIP_API_KEY');
        if (!$apiKey) {
            Log::error('BITESHIP_API_KEY is missing.');
            return response()->json(['error' => 'API Key Configuration Error'], 500);
        }

        $apiEndpoint = 'https://api.biteship.com/v1/maps/areas';
        $params = [
            'countries' => 'ID',
            'input'     => $validated['query'],
            'type'      => 'single' // Hasil level kelurahan/kecamatan
        ];

        Log::info('Searching Biteship Area with params:', $params);
        
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                        ->get($apiEndpoint, $params);

        $responseData = $response->json();

        if ($response->failed() || !$response->successful() || !isset($responseData['success']) || !$responseData['success']) {
            $errorMessage = $responseData['error'] ?? ($responseData['message'] ?? 'Failed to search area');
            Log::error('Biteship Maps API search failed.', ['response' => $responseData]);
            return response()->json(['error' => $errorMessage], 500);
        }

        // Kirim array 'areas' ke frontend
        return response()->json($responseData['areas'] ?? []);
    }
}