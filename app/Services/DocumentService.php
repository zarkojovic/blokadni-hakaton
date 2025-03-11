<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class DocumentService {

    public function generateDocuments($response) {
        // Split the response into two parts based on the delimiter %%%
        $parts = explode('%%%', $response);

        $fileIdiot = $this->generateIdiotDocument($parts[0]);
        $fileTabular = $this->generateTabularOverviewDocument($parts[1]);

        return [
            'fileIdiot' => $fileIdiot,
            'fileTabular' => $fileTabular,
        ];
    }

    private function generateIdiotDocument($response) {
        // Create a new PhpWord object
        $phpWord = new PhpWord();

        // Generate timestamp
        $timestamp = Carbon::now()->format('Ymd_His');

        // Create the document
        $section = $phpWord->addSection();
        $section->addText($response);
        $file = 'Idiot_'.$timestamp.'.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save(public_path('idiots/'.$file));

        return asset('idiots/'.$file);
    }

    private function generateTabularOverviewDocument($response) {
        // Create a new PhpWord object
        $phpWord = new PhpWord();

        // Generate timestamp
        $timestamp = Carbon::now()->format('Ymd_His');

        // Create the document
        $section = $phpWord->addSection();
        $section->addText($response);
        $file = 'Tabular_Overview_'.$timestamp.'.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save(public_path('tabular_overviews/'.$file));

        return asset('tabular_overviews/'.$file);
    }

}
