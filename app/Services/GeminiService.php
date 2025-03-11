<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeminiService {

    protected $client;
    protected $apiKey;

    public function __construct() {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generateResponse($prompt) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$this->apiKey";

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->post($url, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $body = json_decode($response->getBody(), true);
            return $body['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

}
