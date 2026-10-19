<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userMessage = $request->input('message');

        $apiKey = env('GROQ_API_KEY');
        $primaryModel = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $fallbackModel = env('GROQ_FALLBACK_MODEL', 'llama-3.1-8b-instant');

        $models = [$primaryModel, $fallbackModel];

        $responseBody = null;
        $usedModel = null;

        foreach ($models as $model) {
            $model = trim($model);
            if (empty($model)) continue;

            // Endpoint resmi Groq API (kompatibel dengan format OpenAI)
            $url = "https://api.groq.com/openai/v1/chat/completions";

            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $userMessage
                        ]
                    ],
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $responseBody = $data['choices'][0]['message']['content'] ?? null;
                    if ($responseBody) {
                        $usedModel = $model;
                        break;
                    }
                } else {
                    Log::warning("Gagal menggunakan model Groq {$model}: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Exception pada model Groq {$model}: " . $e->getMessage());
            }
        }

        if (!$responseBody) {
            return response()->json([
                'error' => 'Maaf, semua model Groq sedang mengalami gangguan atau API Key Groq salah.'
            ], 500);
        }

        return response()->json([
            'reply' => $responseBody,
            'model_used' => $usedModel
        ]);
    }
}
