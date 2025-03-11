<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeminiController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function generateDocuments(Request $request)
    {
        $response = $this->geminiService->generateResponse("Explain laravel in simple terms");

        return Inertia::render('Dashboard', ['response' => $response]);
    }
}
