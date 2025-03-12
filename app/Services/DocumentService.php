<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class DocumentService {

    private $keys = [
        "Циљ",
        "Циљна група",
        "Бројност",
        "Ризик (репресија, спиновање)",
        "Импакт",
        "Напомена",
        "Тип",
        "Сатница",
        "Место",
        "Тон акције",
        "Обавезне акције",
        "Актери",
        "Трасе",
        "Безбедност",
        "Ширење информација",
    ];

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
        [$title, $content] = explode('###', $response);
        // Create the document section
        $section = $phpWord->addSection();

        // Define title style
        $titleStyle = [
            'bold' => TRUE,
            'size' => 16,
            'alignment' => Jc::CENTER,
        ];

        // Add title to the document
        $section->addText($title, $titleStyle);

        // Define paragraph style
        $paragraphStyle = [
            'spaceAfter' => 200, // Space after paragraph
            'lineHeight' => 1.5, // Line spacing
        ];

        // Add content with paragraph formatting
        $section->addText($content, ['size' => 12], $paragraphStyle);

        // Define file path
        $file = 'Idiot_'.$timestamp.'.docx';

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save(public_path('idiots/'.$file));

        return asset('idiots/'.$file);
    }

    private function generateTabularOverviewDocument($response) {
        // Create a new PhpWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Parse text into key-value array
        $tableValues = $this->parseTextToKeyValueArray($response);

        // Generate timestamp
        $timestamp = \Carbon\Carbon::now()->format('Ymd_His');

        // Create the document section
        $section = $phpWord->addSection();

        // Add title
        $titleStyle = [
            'bold' => TRUE,
            'size' => 16,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ];
        $section->addText("Табеларни приказ", $titleStyle);

        // Define table style with borders
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
        ];
        $phpWord->addTableStyle('TableStyle', $tableStyle);

        // Add a table with the defined style
        $table = $section->addTable('TableStyle');

        // First row: single cell with "ОПИС"
        $table->addRow();
        $cell = $table->addCell(9000, ['gridSpan' => 2]);
        $cell->addText("ОПИС", ['bold' => TRUE, 'size' => 14],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Define the keys to include
        $keys = [
            "Циљ",
            "Циљна група",
            "Бројност",
            "Ризик (репресија, спиновање)",
            "Импакт",
            "Напомена",
        ];

        // Add rows for each key
        foreach ($keys as $key) {
            if (isset($tableValues[$key])) {
                $table->addRow();
                $table->addCell(3000)->addText($key, ['bold' => TRUE]);
                $table->addCell(6000, ['gridSpan' => 2])
                    ->addText($tableValues[$key]);
            }
        }
        $table->addRow();
        $table->addCell(6000, ['gridSpan' => 2])
            ->addText('ПРОЦЕДУРА', ['bold' => TRUE]);
        $table->addCell(3000)->addText('Аутономија');

        // Define the keys to include
        $keys = [
            "Тип",
            "Сатница",
            "Место",
            "Тон акције",
            "Обавезне акције",
            "Актери",
            "Трасе",
            "Безбедност",
            "Ширење информација",
        ];
        foreach ($keys as $key) {
            if (isset($tableValues[$key])) {
                $table->addRow();
                $table->addCell(3000)->addText($key, ['bold' => TRUE]);
                $table->addCell(3000)->addText($tableValues[$key]);
                $table->addCell(3000)->addText('');
            }
        }

        $section->addText("ЛЕГЕНДА АУТОНОМИЈЕ", $titleStyle);

        // generate me a new table with 5 columns and one row with 5 cells with different colors
        $table = $section->addTable($tableStyle);
        $table->addRow();
        $table->addCell(2000)->addText('Аутономија', ['bold' => TRUE]);
        $table->addCell(2000, ['bgColor' => 'D08370'])
            ->addText('КРГС +радионице',
                ['bold' => TRUE]);
        $table->addCell(2000, ['bgColor' => 'FFFF54'])
            ->addText('', ['bold' => TRUE]);
        $table->addCell(2000, ['bgColor' => '5885E1'])
            ->addText('КРГБ +ванредни редари',
                ['bold' => TRUE]);
        $table->addCell(2000, ['bgColor' => 'F19E38'])
            ->addText('КРГМ +ЛОКАЛНЕ РГМ',
                ['bold' => TRUE]);

        // Define file path
        $file = 'Tabular_Overview_'.$timestamp.'.docx';

        // Save the document
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord,
            'Word2007');
        $writer->save(public_path('tabular_overviews/'.$file));

        return asset('tabular_overviews/'.$file);
    }

    private function parseTextToKeyValueArray($text) {
        $result = [];
        foreach ($this->keys as $key) {
            // Kreiranje regexa koji traži vrednost nakon ključne reči
            $pattern = "/$key:\s*(.*?)(?=\n[A-ZА-ЯЁЉЊЂЋЏ]|$)/su";
            if (preg_match($pattern, $text, $matches)) {
                $result[$key] = trim($matches[1]);
            }
        }

        return $result;
    }

}
