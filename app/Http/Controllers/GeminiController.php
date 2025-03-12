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
        if ($request->input('type') == 'content' && $request->isMethod('post') && strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            return response()->json(['content'=>$response]);
        }

        $fileUrls = $this->documentService->generateDocuments($response);

//        if ($request->input('type') == 'table' && $request->isMethod('post') && strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
//
//            return response()->json(['content'=>$fileUrls]);
//        }

        // Return Inertia response with file URLs
        return Inertia::render('Home', $fileUrls);
    }

}
