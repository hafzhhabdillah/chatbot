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
    $validated = $request->validate([
        'message' => 'required|string|max:2000',
    ]);

    $apiKey = config('services.groq.key');

    if (empty($apiKey)) {
        return response()->json([
            'error' => 'API key Groq belum dikonfigurasi.'
        ], 500);
    }

    $models = array_filter([
        config('services.groq.model', 'llama-3.3-70b-versatile'),
        config('services.groq.fallback_model', 'llama-3.1-8b-instant'),
    ]);

    foreach (array_unique($models) as $model) {
        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->timeout(30)
                ->post(
                    'https://api.groq.com/openai/v1/chat/completions',
                    [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $validated['message'],
                            ],
                        ],
                        'temperature' => 0.7,
                    ]
                );

            if ($response->successful()) {
                $reply = $response->json(
                    'choices.0.message.content'
                );

                if (is_string($reply) && trim($reply) !== '') {
                    return response()->json([
                        'reply' => $reply,
                        'model_used' => $model,
                    ]);
                }
            }

            Log::warning('Groq API gagal', [
                'model' => $model,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Koneksi Groq gagal', [
                'model' => $model,
                'message' => $e->getMessage(),
            ]);
        }
    }

    return response()->json([
        'error' => 'Groq gagal memberikan jawaban. Periksa API key, model, dan log Laravel.',
    ], 502);
}

}
