<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateDocumentRequest;
use App\Services\DocumentService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class GeminiController extends Controller {

    protected $geminiService;

    protected $documentService;

    public function __construct(
        GeminiService $geminiService,
        DocumentService $documentService
    ) {
        $this->geminiService = $geminiService;
        $this->documentService = $documentService;
    }

    public function generateDocuments(GenerateDocumentRequest $request) {
        $response = $this->geminiService->generateResponse($request->elaboratFile);
        $fileUrls = $this->documentService->generateDocuments($response);

        // Return Inertia response with file URLs
        return Inertia::render('Dashboard', $fileUrls);
    }

}
